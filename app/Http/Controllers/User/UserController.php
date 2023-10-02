<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//* Models
use App\Models\Project;
use App\Models\WithdrawalRequest;

//* Resources
use App\Http\Resources\User\WithdrawalRequestResource;

//* Utilities
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //TODO =>  Make a withdrawal request
    public function make_withdrawal_request(Request $request){
        try{
            $rules = [
                "user_uuid"=>'required|string',
                'project_uuid'=>'required|string',
            ];

            $validation = Validator::make(request()->all(),$rules);
            if($validation->fails()){
                return response()->json(['status'=>'failed','message'=>$validation->errors()->first()],422);
            }

            $user = auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>'Sorry, User not found !!'],404);
            }

            $project = Project::query()->where('unique_id',request()->project_uuid)->first();
            if(!$project){
                return response()->json(['status'=>'failed','message'=>'Sorry, Project not found'],404);
            }

            //* creating an entry for the withdrawal request
            $withdrawal_request = WithdrawalRequest::query()->create([
                "user_id"=>$user->id,
                "user_uuid"=>$request->user_uuid,
                "project_uuid"=>request()->project_uuid,
                "project_name"=>$project->title,
                "amount_to_be_withdrawn"=>$project->amount_received,
                "approval_status"=>"pending",
                "payout_status"=>"pending",
            ]);

            return response()->json(['status'=>'success','message'=>'Great, your withdrawal request has been sent and it is now being processed. Thank you!!'],200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO => List all the request performed by a user
    public function all_withdrawal_request(){
        try{

            $user = auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>'Sorry, User not found !!'],404);
            }

            $withdrawal_requests = $user->withdrawal_requests;

            if(count($withdrawal_requests)===0){
                return response()->json(['status'=>'success','message'=>'No Withdrawal Request has been issued yet'],200);
            }
            return response()->json(['status'=>'success','withdrawal_requests'=>WithdrawalRequestResource::collection($withdrawal_requests)],200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO => Update a withdrawal request
    public function update_withdrawal_request($id){
        try{
            $user = auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>'User not found!!'],404);
            }

            $withdrawal_request = WithdrawalRequest::query()->where("id",$id)->where('user_id',$user->id)->where('approval_status','!=','approved')->first();
            if(!$withdrawal_request){
                return response()->json(['status'=>'failed', 'message'=>'Request not found or it has already been approved'],400);
            }
            $withdrawal_request->update(request()->all());
            return response()->json(['status'=>'success', 'message'=>'Request successfully updated'],200);

        }catch(Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }
}
