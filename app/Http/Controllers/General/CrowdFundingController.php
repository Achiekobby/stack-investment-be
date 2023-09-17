<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//* Requests
use App\Http\Requests\General\NewProjectRequest;

//* Models
use App\Models\Project;
use App\Models\User;

//* Resources
use App\Http\Resources\General\CrowdFundingProjectResource;

//* Notifications

//* Utilities
use Carbon\Carbon;
use Illuminate\Support\Str;

class CrowdFundingController extends Controller
{
    //TODO => Create a new project to be contributed for
    public function create_project(NewProjectRequest $request){
        try{
            $uuid           = $request->validated()['uuid'];
            $title          = $request->validated()['title'];
            $description    = $request->validated()['description'];
            $amount         = $request->validated()['amount'];

            //* extract logged in user using the auth token
            $logged_in_user = auth()->guard('api')->user();

            //* insert the project in the db table
            $new_project = Project::query()->create([
                "unique_id" => Str::uuid(),
                "user_id"   => $logged_in_user->id,
                "title"=>$title,
                "description"=>$description,
                "amount_requested"=>number_format((float)$amount,2,'.',''),
                "amount_received"=>"0.00",
            ]);
            if($new_project){
                return response()->json(['status'=>"success","message"=>"Your new project is under review. Please check back within 24 hours for approval status"],201);
            }
            else{
                return response()->json(['status'=>"failed","message"=>"Sorry, New project creation failed, please try again"],400);
            }

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO:: Show a details of project
    public function update(NewProjectRequest $request, $unique_id){
        try{
            $user = auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>"User not found"],404);
            }

            //* check if the user owns the project to be edited
            $project = Project::query()->where('user_id',$user->id)->where("unique_id",$unique_id)->where('project_status','!=','completed')->first();
            if(!$project){
                return response()->json(['status'=>'failed','message'=>'Sorry, project has already been closed or does not belong to logged in user'],400);
            }

            $uuid           = $request->validated()['uuid'];
            $title          = $request->validated()['title'];
            $description    = $request->validated()['description'];
            $amount         = $request->validated()['amount'];

            $project_update = $user->projects()->where('unique_id',$unique_id)->update([
                "unique_id"         =>$unique_id,
                "user_id"           => $user->id,
                "title"             =>$title,
                "description"       =>$description,
                "amount_requested"  =>number_format((float)$amount,2,'.',''),
                "approval"          =>"pending"
            ]);
            if($project_update){
                return response()->json(['status'=>"success","message"=>"Your project has been updated."],201);
            }
            else{
                return response()->json(['status'=>"failed","message"=>"Sorry, Project update failed"],400);
            }

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO=> Show a specific project details
    public function show($unique_id){
        try{
            $user = auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>"User not found"],404);
            }

            $project = $user->projects()->where([['unique_id',$unique_id],['user_id',$user->id]])->first();
            if(!$project){
                return response()->json(['status'=>'failed','message'=>'Sorry, Project was not found'],404);
            }
            return response()->json(['status'=>'success','project'=>new CrowdFundingProjectResource($project)],200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO=> Show all the projects which have been created by the logged in user
    public function index(){
        try{
            $user = auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>"User not found"],404);
            }

            $projects = $user->projects()->where('user_id',$user->id)->get();
            if(count($projects)===0){
                return response()->json(['status'=>'failed','message'=>'Sorry, User has not created any project yet!!'],404);
            }
            return response()->json(['status'=>'success','projects'=>CrowdFundingProjectResource::collection($projects)],200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO =>API FOR PENDING APPROVAL
    public function pending_project(){
        try{
            $user = auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>"User not found"],404);
            }
            $projects = $user->projects()->where([['user_id',$user->id],['approval','pending']])->get();
            if(count($projects)===0){
                return response()->json(['status'=>'failed','message'=>'Sorry, User has not created any project yet!!'],404);
            }
            return response()->json(['status'=>'success','pending_projects'=>CrowdFundingProjectResource::collection($projects)],200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }


    //TODO=> Show a specific project details
    public function remove($unique_id){
        try{
            $user = auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>"User not found"],404);
            }

            $project = $user->projects()->where([['unique_id',$unique_id],['user_id',$user->id],["project_status","=","completed"]])->first();
            if(!$project){
                return response()->json(['status'=>'failed','message'=>'Sorry, Cannot remove an ongoing project'],404);
            }

            $project->delete();
            return response()->json(['status'=>'success','message'=>'Great, you have successfully removed this project'],200);
        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO=> SHOW ALL OPEN PROJECTS
    public function all_open_projects(){
        try{
            $projects = Project::query()->where([['project_status',"active"],["approval","approved"]])->get();
            if(count($projects)===0){
                return response()->json(['status'=>"failed","message"=>"No Approved Project Yet. Still Loading"],404);
            }
            else{
                return response()->json(['status'=>"success","message"=>"Great, Here is a list of Approved Projects","projects"=>CrowdFundingProjectResource::collection($projects)],200);
            }

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }
}
