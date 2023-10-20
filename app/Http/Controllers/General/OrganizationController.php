<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//* Models
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\User;
use App\Models\Invitation;

//* Requests
use App\Http\Requests\General\NewOrganizationRequest;

//* Resources
use App\Http\Resources\General\OrganizationResource;
use App\Http\Resources\User\UserDetailsResource;
use App\Http\Resources\InvitationResource;

//* Utilities
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

//* Notifications
use App\Notifications\InvitationNotification;

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

    //TODO=> Extract all the groups created by the logged in user
    public function extract_user_group(){
        try{
            $user = auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>'Sorry, user not found'],404);
            }

            //* Extracting groups where the user is a group leader of
            $groups = $user->organizations()->get();
            return response()->json(['status'=>'success',''=>OrganizationResource::collection($groups)],200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage],500);
        }
    }

    //TODO=> Invite a user to the group
    public function invite_user_to_group(){
        try{
            $rules = [
                "email"=>'required|email',
                "group_uuid"=>"required|string"
            ];

            $validation = Validator::make(request()->all(),$rules);
            if($validation->fails()){
                return response()->json(['status'=>'failed','message'=>$validation->errors()->first()],422);
            }

            $logged_in_user = auth()->guard('api')->user();
            if(!$logged_in_user){
                return response()->json(['status'=>'failed','message'=>'User not found!'],404);
            }
            $group  = Organization::query()->where('unique_id',request()->group_uuid)
                                            ->where('user_id',$logged_in_user->id)
                                            ->where('status','active')
                                            ->where('approval','approved')
                                            ->first();
            if(!$group){
                return response()->json(['status'=>'failed','message'=>'Sorry, this group does not meet the requirement for an invite'],400);
            }

            $invited_user = User::query()->where('email',request()->email)->first();
            //* send invite notifications for the user
            $new_invite = $group->invitations()->updateOrCreate(["email"=>$invited_user->email,"organization_id"=>$group->id],[
                "uuid"          =>Str::uuid(),
                "user_name"     =>Str::title($invited_user->first_name." ". $invited_user->last_name),
                "email"         =>$invited_user->email,
                "user_phone"    =>$invited_user->phone_number,
                "link"          =>null,
                "status"        =>"pending"
            ]);

            //* email_data
            $mail_data = [
                "user_name"     =>Str::title($invited_user->first_name." ". $invited_user->last_name),
                'user_phone'    =>$invited_user->phone_number,
                "email"         =>$invited_user->email,
                "group_title"   =>$group->title,
                "link"          =>config('services.url.frontend_url')
            ];

            $invited_user->notify(new InvitationNotification($mail_data));

            return response()->json(['status'=>'success','message'=>'Great, you have invited this user'],200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO=> List all invites of a user
    public function extract_all_user_invites(){
        try{
            $user = auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>'Sorry, user not found'],404);
            }

            $invites = Invitation::where('email',$user->email)->where('status','pending')->get();
            return response()->json(['status'=>'success','invites'=>InvitationResource::collection($invites)],200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO=>Accept or Decline an Invitation to a group
    public function handle_invitation(){
        try{
            $rules =[
                "response"=>"required|string",
                "invitation_uuid"=>"required|string",
            ];

            $invitation_uuid = request()->invitation_uuid;

            $validation = Validator::make(request()->all(),$rules);
            if($validation->fails()){
                return response()->json(['status'=>'failed','message'=>$validation->errors()->first()],422);
            }

            $user = auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>'Sorry, user not found'],404);
            }
            if(request()->response === "accepted"){
                //* extract the invitation to be accepted
                $invitation = Invitation::query()->where('email', $user->email)->where('uuid',$invitation_uuid)->where('status','=','pending')->first();
                if(!$invitation){
                    return response()->json(['status'=>'failed','message'=>'Sorry, This invitation has already have already been declined or Accepted'],400);
                }

                //* update the invitation to accepted
                $invitation->update(['status'=>'accepted']);

                //* find the corresponding group to be added
                $group = Organization::where('id',$invitation->organization_id)->first();
                $group->update(['number_of_participants'=>$group->number_of_participants+1]);
                $group->members()->create([
                    "user_id"=>$user->id,
                    "role"=>"member",
                    "name"=>$user->first_name." ".$user->last_name,
                    "phone_number"=>$user->phone_number,
                    "email"=>$user->email,
                    "status"=>"active",
                    "amount_to_be_taken"=>number_format((float)($group->amount_per_cycle),2,'.',''),
                    "number_of_payments"=>$group->max_number_of_members,
                    "received_benefit"=>"false"
                ]);

                return response()->json(['status'=>'success','message'=>'Great, you have successfully accepted this invite'],200);
            }
            else if(request()->response ==="declined"){
                //* extract the invitation to be accepted
                $invitation = Invitation::query()->where('email', $user->email)->where('uuid',$invitation_uuid)->where('status','=','pending')->first();
                if(!$invitation){
                    return response()->json(['status'=>'failed','message'=>'Sorry, This invitation has already have already been accepted or declined'],400);
                }
                $invitation->update(['status'=>'declined']);

                return response()->json(['status'=>'success','message'=>'Great, you have successfully declined this invite'],200);
            }

        }catch(\Exception $e){
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()),500);
        }
    }

    //TODO=> Search list of users using the email
    public function search_users(){
        try{

            $email = request()->email;
            $user = User::where('email',$email)->first();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>'No user has the specified email address'],404);
            }
            return response()->json(['status'=>'success','user'=>new UserDetailsResource($user)],200);

        }catch(\Exception $e){
            return response()->json(['status'=>"failed",'message'=>$e->getMessage()],500);
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
                $maturity           = $req->validated(['maturity']);
                $number_of_members  = $req->validated(['number_of_members']);
                $amount_per_member  = $req->validated(['amount_per_member']);
                // $start_date         = $req->validated(['start_date']);

                //* check if the number of  members exceeds 12 or below 3
                if(request()->number_of_members <3 || request()->number_of_members >12){
                    return response()->json(['status'=>'failed','message'=>'Sorry, the minimum number of members to add is 3 and max number of 12'],400);
                }


                $new_organization = Organization::query()->create([
                    "unique_id"         =>Str::uuid(),
                    "user_id"           =>$user->id,
                    "number_of_participants"=>1,
                    "title"             =>$title,
                    "max_number_of_members"=>request()->number_of_members,
                    "description"       =>$description ?? null,
                    "cycle_period"      =>$maturity,
                    "currency"          =>"GHS",
                    "number_of_cycles"  =>request()->number_of_members,
                    "commencement_date" =>null,
                    "amount_per_member" =>$amount_per_member,
                    "amount_per_cycle"  =>number_format((float)(request()->number_of_members*$amount_per_member),2,'.',''),
                    "status"            =>"inactive",
                    "approval"          =>"pending"
                ]);

                //* create the team leader
                $new_organization->members()->create([
                    "user_id"=>$user->id,
                    "role"=>"group_admin",
                    "name"=>$user->first_name." ".$user->last_name,
                    "phone_number"=>$user->phone_number,
                    "email"=>$user->email,
                    "status"=>"active",
                    "amount_to_be_taken"=>number_format((float)(request()->number_of_members*$amount_per_member),2,'.',''),
                    "number_of_payments"=>request()->number_of_members,
                    "received_benefit"=>"false"
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
                $maturity           = $req->validated(['maturity']);
                $number_of_members  = $req->validated(['number_of_members']);
                $amount_per_member  = $req->validated(['amount_per_member']);
                $start_date         = $req->validated(['start_date']);

                //* check if the number of  members exceeds 12 or below 3
                if(request()->number_of_members <3 || request()->number_of_members >12){
                    return response()->json(['status'=>'failed','message'=>'Sorry, the minimum number of members to add is 3 and max number of 12'],400);
                }

                //* if the number of members is less than already added
                if(request()->number_of_members < $organization->number_of_participants){
                    return response()->json(['status'=>'failed','message'=>'Sorry, you have included more users to this group than the max number of participants specified for this group'],400);
                }


            $updated_organization = $organization->update([
                "user_id"           =>$user->id,
                "number_of_participants"=>$organization->number_of_participants,
                "title"             =>$title,
                "max_number_of_members"=>request()->number_of_members,
                "description"       =>$description ?? null,
                "cycle_period"      =>$maturity,
                "currency"          =>"GHS",
                "number_of_cycles"  =>request()->number_of_members,
                "commencement_date" =>$start_date,
                "amount_per_member" =>$amount_per_member,
                "amount_per_cycle"  =>number_format((float)(request()->number_of_members*$amount_per_member),2,'.',''),
                "status"            =>"inactive",
                "approval"          =>"pending"
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

            $member_ids = OrganizationMember::query()->where("organization_id",$organization->id)->pluck('user_id')->toArray();
            if(in_array($member->id, $member_ids)){
                return response()->json(['status'=>'failed','message'=>"Sorry, you have already added this user as a member of this organization!!"],400);
            }

            if($organization->user_id !== $team_leader->id){
                return response()->json(['status'=>"failed","message"=>"Sorry, this organization does not belong to you"],400);
            }

            //* checking the number of members added so far so that the members do not exceed the 12 member threshold
            $members = $organization->members;
            if(count($members) >=12){
                return response()->json(['status'=>'failed','message'=>'Sorry, you have exceeded the number of members to add to this scheme!!'],400);
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
                "phone_number" =>$phone,
                "status"=>"active",
                "amount_to_be_taken"=>number_format((float)($organization->amount_per_member * $organization->number_of_cycles),2,'.',''),
                "number_of_payments"=>$organization->max_number_of_members,
                "received_benefit"=>"false"
            ]);

            if($new_member){
                $organization->update(['number_of_participants'=>$organization->number_of_participants + 1]);
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

