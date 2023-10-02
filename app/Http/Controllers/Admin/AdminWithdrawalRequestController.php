<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//*Models
use App\Models\WithdrawalRequest;

//* Resources
use App\Http\Resources\Admin\AdminWithdrawalRequestResource;

//* Utilities
use Illuminate\Support\Facades\Validator;

class AdminWithdrawalRequestController extends Controller
{
    //TODO => List all the withdrawal requests
    public function pending_withdrawal_requests(){
        try{
            $user = auth()->guard('api')->user();
            if($user && ($user->role ==="super_admin"||$user->role ==="admin")){
                $withdrawal_request_all = WithdrawalRequest::query()->where("approval_status","pending")->get();
                if(count($withdrawal_request_all)===0){
                    return response()->json(['status'=>'success','message'=>'Sorry, no withdrawal request in the system yet!!'],200);
                }
                return response()->json(['status'=>'success','all_requests'=>AdminWithdrawalRequestResource::collection($withdrawal_request_all)],200);
            }
            return response()->json(['status'=>'failed','message'=>'User not found or User does not have the right permissions for this action'],401);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO => Change status for a withdrawal request
    public function change_withdrawal_request_status(){
        try{
            $rules = [
                "approval_status"=>"required|string",
                "withdrawal_id"=>"required|string",
                "reason"=>"nullable|string"
            ];

            $validation = Validator::make(request()->all(),$rules);
            if($validation->fails()){
                return response()->json(['status'=>'failed','message'=>$validation->errors()->first()],422);
            }

            $user = auth()->guard('api')->user();
            if($user && ($user->role ==="super_admin"||$user->role ==="admin")){

                //* Fetch the withdrawal
                $withdrawal_request = WithdrawalRequest::query()->where("id",request()->withdrawal_id)->first();

                //* case 1: when the approval_status = "approved".
                if(request()->approval_status === "approved"){
                    $withdrawal_request->update(['approval_status'=>"approved"]);
                    return response()->json(['status'=>"success", "message"=>"Great, you have successfully approved this withdrawal request"],200);
                }

                //* case 2: when the approval status=disapproved
                if(request()->approval_status === "denied"){
                    if(request()->reason ==="" || is_null(request()->reason)){
                        return response()->json(['status'=>"failed",'message'=>"Please add the reason for approval denial for this request"],400);
                    }

                    $withdrawal_request->update([
                        "approval_status"       =>"denied",
                        "reason_for_rejection"  =>request()->reason,
                        "payout_status"         =>"denied"
                    ]);

                    return response()->json(['status'=>"success",'message'=>"Great, you have successfully denied this withdrawal request"],200);
                }
            }
            return response()->json(['status'=>'failed','message'=>'User not found or User does not have the right permissions for this action'],401);
        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }
}
