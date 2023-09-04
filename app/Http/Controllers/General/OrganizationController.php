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
use App\Http\Resources\User\UserDetailsResource;

//* Utilities
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    //TODO:: Search query to find a registered user for an organization which will aid the team leader to add new members to the organization.
    public function search_registered_users(){
        try{
            $query = request()->input("name");
            if(!$query){
                return response()->json(['status'=>"failed","message"=>'Search requires a parameter to work!!'],422);
            }
            $search_query = User::query();
            $full_name = request()->input("name");
            $full_name_parts = explode(" ", $full_name);

            $search_query->where(function($q) use ($full_name, $full_name_parts) {
                if (count($full_name_parts) === 1) {
                    $q->where("first_name", "like", "%" . $full_name_parts[0] . "%")
                        ->orWhere("last_name", "like", "%" . $full_name_parts[0] . "%");
                } else {
                    $q->where(function ($q) use ($full_name, $full_name_parts) {
                        $q->whereRaw("CONCAT(first_name, ' ', last_name) = ?", [$full_name]);
                    })->orWhere(function ($q) use ($full_name_parts) {
                        $q->where("first_name", "like", "%" . $full_name_parts[0] . "%")
                            ->where("last_name", "like", "%" . $full_name_parts[1] . "%");
                    })->orWhere(function ($q) use ($full_name_parts) {
                        $q->where("first_name", "like", "%" . $full_name_parts[1] . "%")
                            ->where("last_name", "like", "%" . $full_name_parts[0] . "%");
                    });
                }
            });

            $results = $search_query->get();

            return response()->json(['status'=>'success',"user"=>UserDetailsResource::collection($results)],200);
        }catch(\Exception $e){
            return response()->json(['status'=>"failed","message"=>$e->getMessage()],500);
        }
    }


    //TODO:: Adding new members to an organization
    public function add_members_to_organization($unique_id){
        try{
            $rules = [
                "member_uuid"=>"required|string"
            ];
            $validation = Validator::make(request()->all(),$rules);
            if($validation->fails()){
                return response()->json(['status'=>"failed","message"=>$validation->errors()->first()]);
            }
            $member = User::query()->where("uuid",request()->member_uuid)->first();
            if(!$member){
                return response()->json(['status'=>"failed","message"=>"Sorry, the user you are trying to add is not a registered user in the application"],404);
            }

            $organization = Organization::query()->where("unique_id",$unique_id)->where("status","!=","completed")->orWhere("status","!=","active")->first();
            if(!$organization){
                return response()->json(['status'=>"failed","message"=>"Sorry, you cannot add members to an active or complete organization"],400);
            }

            $team_leader = auth()->guard('api')->user();
            if(!$team_leader){
                return response()->json(['status'=>"failed","message"=>"Sorry, user must be logged in to perform this function"],404);
            }

            $organization_team_leader = OrganizationMember::query()->where("organization_id",$organization->id)
                                                                    ->where("user_id",$team_leader->id)
                                                                    ->where("role","team_leader")
                                                                    ->first();

            if($organization_team_leader->user_id === $member->id) {
                return response()->json(['status'=>'failed','message'=>"Sorry, you are already the team leader of this organization"],400);
            }

            if($organization->user_id !== $team_leader->id){
                return response()->json(['status'=>"failed","message"=>"Sorry, this organization does not belong to you"],400);
            }

            //* data needed for the creating a member of an organization
            $user_id    = $member->id;
            $role       = "member";
            $name       = $member->first_name." ".$member->last_name;
            $email      = $member->email;
            $phone      = $member->phone_number;

            $new_member = $organization->members()->create([
                "user_id"=>$user_id,
                "role"  =>$role,
                "name"  =>$name,
                "email" =>$email,
                "phone_number" =>$phone
            ]);

            if($new_member){
                return response()->json(['status'=>"success","message"=>"Great, new member has been added to this organization"],200);
            }
            else{
                return response()->json(['status'=>"failed","message"=>"Sorry, the member could not be added."],400);
            }

        }catch(\Exception $e){
            return response()->json(['status'=>"failed","message"=>$e->getMessage()],500);
        }
    }

}

