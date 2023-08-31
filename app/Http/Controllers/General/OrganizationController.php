<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//* Models
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\User;

//* Requests
use App\Http\Requests\General\NewOrganizationRequest;

//* Resources
use App\Http\Resources\General\OrganizationResource;

//* Utilities
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    //TODO=> Extract all organizations a logged in user has been part of regardless of whether it has been closed or not

    public function index(){
        try{
            //* Extracting all the organizations a user is part of.
            $user = auth()->guard('api')->user();
            $organization = $user->organizations;
            return response()->json(['status'=>"success","user_organizations"=>OrganizationResource::collection($organization)],200);
        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO=> Extract all organizations a logged in user has been part of which is still active.
    public function active_organizations(){
        try{
            $user = auth()->guard('api')->user();
            $organizations = Organization::whereIn('id', function($query) use ($user) {
                $query->select('organization_id')
                    ->from('organization_members')
                    ->where('user_id', $user->id);
            })
            ->where('status', 'active')
            ->get();
            return response()->json(['status'=>"success","user_organizations"=>OrganizationResource::collection($organizations)],200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO=> Create a new organization and submit for approval
    public function create_organization(NewOrganizationRequest $req){
        try{
            $user =auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed',"message"=>"Sorry, user not found"],404);
            }

            $organization = DB::transaction(function() use ($user, $req) {
                $title              = $req->validated(['title']);
                $description        = $req->validated(['description']);
                $cycle_period       = $req->validated(['cycle_period']);
                $amount_per_member  = $req->validated(['amount_per_member']);
                $start_date         = $req->validated(['start_date']);

                $new_organization = Organization::query()->create([
                    "unique_id"         =>Str::uuid(),
                    "user_id"           =>$user->id,
                    "number_of_participants"=>1,
                    "title"             =>$title,
                    "description"       =>$description,
                    "cycle_period"      =>$cycle_period,
                    "commencement_date" =>$start_date,
                    "amount_per_member" =>$amount_per_member,
                    "status"            =>"inactive"
                ]);

                //* create the team leader
                $new_organization->members()->create([
                    "user_id"=>$user->id,
                    "role"=>"team_leader",
                    "name"=>$user->first_name." ".$user->last_name,
                    "phone_number"=>$user->phone_number,
                    "email"=>$user->email,
                    "status"=>"active"
                ]);

                return $new_organization;
            });

            if($organization){
                return response()->json(['status'=>'success',"organization"=>new OrganizationResource($organization)],200);
            }
            else{
                return response()->json(['status'=>'failed',"message"=>"Sorry, A problem occurred during the creation of the organization. Please try again"],400);
            }


        }catch(\Exception $e){
            return response()->json(['status'=>"success","message"=>$e->getMessage()],500);
        }
    }

    //TODO=> Show Specific organization
    public function show_organization($unique_id){
        try{
            $user =auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed',"message"=>"Sorry, user not found"],404);
            }
            $organization = Organization::where('unique_id',$unique_id)->first();
            if(!$organization){
                return response()->json(['status'=>'failed',"message"=>"Sorry, Specified unique id is not associated to any organization"],404);
            }
            return response()->json(['status'=>'success',"organization"=>new OrganizationResource($organization)],200);

        }catch(\Exception $e){
            return response()->json(['status'=>"success","message"=>$e->getMessage()],500);
        }
    }

    //TODO=>Edit the Specific Organization
    public function update_organization(NewOrganizationRequest $req, $unique_id){
        try{
            $user =auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed',"message"=>"Sorry, user not found"],404);
            }

            $organization = Organization::query()->where([['user_id',$user->id],['unique_id',$unique_id],['status','inactive']])->first();
            if(!$organization){
                return response()->json(['status'=>'failed',"message"=>"Sorry, Specified unique id is not associated to any organization"],404);
            }

            //* update the organization
            $title              = $req->validated(['title']);
            $description        = $req->validated(['description']);
            $cycle_period       = $req->validated(['cycle_period']);
            $amount_per_member  = $req->validated(['amount_per_member']);
            $start_date         = $req->validated(['start_date']);

            $updated_organization = $organization->update([
                "unique_id"         =>$organization->unique_id,
                "user_id"           =>$user->id,
                "number_of_participants"=>$organization->number_of_participants,
                "title"             =>$title,
                "description"       =>$description,
                "cycle_period"      =>$cycle_period,
                "commencement_date" =>$start_date,
                "amount_per_member" =>$amount_per_member,
                "status"            =>"inactive"
            ]);
            if($updated_organization){
                return response()->json(['status'=>'success',"message"=>"Organization updated successfully"],200);
            }
            else{
                return response()->json(["status"=>"failed","message"=>"Sorry, organization update failed"]);
            }
        }catch(\Exception $e){
            return response()->json(['status'=>"success","message"=>$e->getMessage()],500);
        }
    }


    //TODO=> Show a specific project details
    public function remove($unique_id){
        try{
            $user = auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>"User not found"],404);
            }

            $organization = $user->organizations()
                                ->where([
                                    ['organizations.unique_id', $unique_id],
                                    ['organization_members.user_id', $user->id],
                                    ['organizations.status', '!=', 'active']
                                ])
                                ->first();
            if(!$organization){
                return response()->json(['status'=>'failed','message'=>'Sorry, Cannot remove an ongoing project or project could not be found'],404);
            }

            $organization->delete();
            return response()->json(['status'=>'success','message'=>'Great, you have successfully removed this project'],200);
        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }
}

