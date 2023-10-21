<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//* Models
use App\Models\Organization;
use App\Models\User;

//* Resources
use App\Http\Resources\General\OrganizationResource;

//* Notifications
use App\Notifications\P2P\OrganizationApprovalNotification;
use App\Notifications\P2P\OrganizationRejectionNotification;

//* utilities
use Carbon\Carbon;
use Illuminate\Support\Facades\Support;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AdminOrganizationController extends Controller
{

    public $admin;

    //TODO=> Constructor to check if the logged in user is an Admin
    public function __construct(){
        $admin = auth()->guard('api')->user();
        $this->admin = $admin;

        $is_admin = ($admin->role === 'admin' || $admin->role === 'super_admin') ? true : false;
        if(!$is_admin){
            abort(403, "Only admin can access this functionality");
        }
    }

    //TODO=> List all crowdfunding projects
    public function list_organizations(){
        try{
            $organizations = Organization::query()->orderBy("created_at", "DESC")->get();
            return response()->json(['status'=>'success','organizations'=>OrganizationResource::collection($organizations)],200);

        }catch(\Exception $e){
            return response()->json(array("status"=>"failed","message"=>$e->getMessage()),500);
        }
    }

    //TODO=> Approve a Crowdfunding Project
    public function approve_organization($unique_id){
        try{
            $organization = Organization::query()->where("unique_id",$unique_id)->where("approval","!=","approved")->first();
            if(!$organization){
                return response()->json(['status'=>'failed',"message"=>'Sorry,This organization was not found or it has already been approved'],400);
            }
            $organization->update([
                'approval'  =>"approved",
                // 'status'    =>'active',
            ]);

            //* Notification to indicate that the organization has been approved
            $team_leader = User::where("id",$organization->user_id)->first();
            $name        = Str::title($team_leader->first_name)." ".Str::title($team_leader->last_name);

            //* Notification to indicate that the project has been rejected
            $team_leader->notify(new OrganizationApprovalNotification($name));
            return response()->json(['status'=>"success",'message'=>'You successfully approved this P2P Organization'],200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed', 'message'=>$e->getMessage()],500);
        }
    }

    //TODO:: Reject a crowdfunding project by an administrator
    public function reject_organization(){
        try{
            $rules = ["unique_id"=>"required|string","reason"=>"required|string"];
            $validation = Validator::make(request()->all(),$rules);
            if($validation->fails()){
                return response()->json(['status'=>'failed',"message"=>$validation->errors()->first()],422);
            }
            $unique_id = request()->unique_id;
            $organization = Organization::query()->where("unique_id",$unique_id)->where("approval","pending")->where("approval","!=","approved")->first();
            if(!$organization){
                return response()->json(['status'=>'failed',"message"=>'Sorry,This P2P Organization was not found or it has already been approved'],400);
            }
            $team_leader = User::query()->where("id",$organization->user_id)->first();
            $name = Str::title($team_leader->first_name)." ".Str::title($team_leader->last_name);
            $message = request()->reason;

            $organization->update([
                "approval"=>"rejected",
            ]);
            $team_leader->notify(new OrganizationRejectionNotification($name, $message));

            return response()->json(['status'=>'success',",message"=>"Successfully rejected this P2P Organization."],200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed', 'message'=>$e->getMessage()],500);
        }
    }

}
