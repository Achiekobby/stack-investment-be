<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//* Models
use App\Models\Project;
use App\Models\User;

//* Resources
use App\Http\Resources\General\CrowdFundingProjectResource;

//*Notifications
use App\Notifications\CrowdFunding\ProjectApprovalNotification;
use App\Notifications\CrowdFunding\ProjectRejectionNotification;


//* Utilities
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AdminCrowdfundingOperationsController extends Controller
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
    public function list_projects(){
        try{
            $projects = Project::query()->orderBy("created_at", "DESC")->get();
            return response()->json(['status'=>'success','projects'=>CrowdFundingProjectResource::collection($projects)],200);

        }catch(\Exception $e){
            return response()->json(array("status"=>"failed","message"=>$e->getMessage()),500);
        }
    }

    //TODO=> Approve a Crowdfunding Project
    public function approve_project($unique_id){
        try{
            $project = Project::query()->where("unique_id",$unique_id)->where("approval","!=","approved")->first();
            if(!$project){
                return response()->json(['status'=>'failed',"message"=>'Sorry,This crowdfunding project was not found or it has already been approved'],400);
            }
            $project->update(['approval'=>"approved","approved_on"=>Carbon::now()->format('Y-m-d H:i:s'),"project_status"=>"active"]);

            //* Notification to indicate that the project has been approved
            $team_leader = User::where("id",$project->user_id)->first();
            $name = Str::title($team_leader->first_name)." ".Str::title($team_leader->last_name);

            //* Notification to indicate that the project has been rejected
            $team_leader->notify(new ProjectApprovalNotification($name));

            return response()->json(['status'=>"success",'message'=>'You successfully approved this crowdfunding project'],200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed', 'message'=>$e->getMessage()],500);
        }
    }

    //TODO:: Reject a crowdfunding project by an administrator
    public function reject_project(){
        try{
            $rules = ["unique_id"=>"required|string","reason"=>"required|string"];
            $validation = Validator::make(request()->all(),$rules);
            if($validation->fails()){
                return response()->json(['status'=>'failed',"message"=>$validation->errors()->first()],422);
            }
            $unique_id = request()->unique_id;
            $project = Project::query()->where("unique_id",$unique_id)->where("approval","pending")->where("approval","!=","approved")->first();
            if(!$project){
                return response()->json(['status'=>'failed',"message"=>'Sorry,This crowdfunding project was not found or it has already been approved'],400);
            }
            $team_leader = User::query()->where("id",$project->user_id)->first();
            $name = Str::title($team_leader->first_name)." ".Str::title($team_leader->last_name);
            $message=request()->reason;

            $project->update([
                "approval"=>"rejected",
            ]);

            $team_leader->notify(new ProjectRejectionNotification($name, $message));

            return response()->json(['status'=>'success',",message"=>"Successfully rejected this crowdfunding project"],200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed', 'message'=>$e->getMessage()],500);
        }
    }

}
