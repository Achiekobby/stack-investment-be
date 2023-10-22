<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//* Models
use App\Models\GroupWithdrawalRequest;
use App\Models\GroupPayout;
use App\Models\User;
use App\Models\Organization;
use App\Models\PayoutMethod;

//* Resources
use App\Http\Resources\Admin\GroupPayoutRequestResource;

//* utilities
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


class AdminP2PTransactionController extends Controller
{
    //TODO=> LIST ALL THE ACTIVE AND UNPAID PAYOUT REQUEST FOR ALL THE GROUPS IN THE APPLICATION
    public function payout_request(){
        try{
            $user =auth()->guard('api')->user();
            if(!$user && ($user->role!=="admin" || $user->role!=="super_admin")){
                return response()->json(['status'=>'failed','message'=>'Sorry, only admins and super admins can access this information'],401);
            }
            return response()->json([
                "status"=>"success",
                "payout_requests"=>GroupPayoutRequestResource::collection(GroupWithdrawalRequest::query()->get())
            ]);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO=> INITIATE THE PAYOUT PROCESS FOR AN UNPAID GROUP PAYOUT REQUEST
    // public function pay_vendor_initialization(){
    //     try{
    //         $rules = [
    //             'payout_id' =>'required',
    //         ];

    //         $validation = Validator::make(request()->all(),$rules);
    //         if($validation->fails()){
    //             return response()->json(['status'=>'failed','message'=>$validation->errors()->first()],422);
    //         }

    //         $user = auth()->guard('api')->user();
    //         if(!$user && ($user->role!=="admin" || $user->role!=="super_admin")){
    //             return response()->json(['status'=>'failed','message'=>'Sorry, ony admins and super admins can access this information'],401);
    //         }

    //         //* Extract the item being paid for and then checking the status
    //         $payout_data = GroupWithdrawalRequest::where('id',request()->payout_id)->where('status','processing')->first();
    //         if(!$payout_data){
    //             return response()->json(['status'=>'failed','message'=>'Sorry, payout request for this product has been already completed!'],400);
    //         }
    //         $team_leader = User::where('id',$payout_data->user_id)->first();
    //         if(!$team_leader){
    //             return response()->json(['status'=>'failed','message'=>'Group Admin does not exist'],404);
    //         }

    //         $group = Organization::query()->where('id',$payout_data->organization_id)->where('user_id',$team_leader->id)->first();
    //         if(!$group){
    //             return response()->json(['status'=>'failed','message'=>'This product does not belong to specified vendor'],404);
    //         }

    //         $transfer_method = PayoutMethod::query()->where('user_id',$team_leader->id)->where('status','active')->first();
    //         if(!$transfer_method){
    //             return response()->json(['status'=>'failed','message'=>'This vendor has not provided any payment method. Please notify the vendor'],400);
    //         }


    //         $recipient_code = $transfer_method->recipient_code;

    //         //* check if the code has not exceeded the 30 minutes mark
    //         $payout_exists = GroupPayout::where('withdrawal_request_id',$payout_data->id)
    //                                     ->where('recipient_id', $team_leader->id)
    //                                     ->where('status','initialized')
    //                                     ->first();

    //         //* Initiate the Transfer.
    //         $response = '';
    //         if(!$payout_exists || Carbon::parse($payout_exists->initialized_at)->addMinutes(30)->lt(Carbon::now())){
    //             $transfer_url           = config('services.payment_url.paystack')."/transfer";
    //             $paystack_secret_key    = config('services.paystack.secret_key');
    //             $amount_to_transfer     = number_format((float)($payout_data->amount_to_withdraw *100),2,'.','');

    //             $response = Http::withHeaders([
    //                 'Authorization' =>'Bearer '.$paystack_secret_key,
    //                 'Content-Type'  =>'application/json'
    //             ])->post($transfer_url, [
    //                 'source'    =>'balance',
    //                 'amount'    =>(int)$amount_to_transfer,
    //                 'recipient' =>$recipient_code,
    //                 'reason'    =>'Group Withdrawal Request Payout for product',
    //                 'currency'  =>'GHS',
    //             ])->json();
    //             if($response['status'] === false){
    //                 return response()->json(['status'=>'failed','message'=>$response['message']],404);
    //             }
    //             //* CREATE A PAYOUT ENTRY FOR THE VENDOR
    //             $payout = GroupPayout::query()->updateOrCreate(
    //                 [
    //                     'withdrawal_request_id'     =>$payout_data->id,
    //                     'recipient_id'              =>$team_leader->id,
    //                     'recipient_code'            =>$recipient_code,
    //                     'finalized_at'              =>null,
    //                     'status'                    =>'initialized'
    //                 ],
    //                 [
    //                     'admin_id'      =>$user->id,
    //                     'withdrawal_request_id'     =>$payout_data->id,
    //                     'recipient_id'     =>$team_leader->id,
    //                     'recipient_code'=>$recipient_code,
    //                     'status'        =>'initialized',
    //                     'transfer_code' =>$response['data']['transfer_code'],
    //                     'amount_payable'=>number_format((float)($payout_data->amount_to_withdraw),2,'.',''),
    //                     'initialized_at'=>Carbon::now()->format('Y-m-d H:i:s'),
    //                     'finalized_at'  =>null
    //                 ]
    //             );
    //             //* UPDATE THE ORDER ITEM STATUS TO INITIALIZED
    //             GroupWithdrawalRequest::where('organization_id',$group->id)->where('status','processing')->update(['status'=>'initialized']);
    //             return response()->json(
    //                 [
    //                     'status'        =>'success',
    //                     'message'       =>'You have successfully initialized the transfer of GHS.'.number_format((float)($payout_data->amount_to_withdraw),2,'.','').' to selected vendor' ,
    //                     'transfer_code' =>$response['data']['transfer_code'],
    //                     'recipient_name'=>$team_leader->first_name.' '.$team_leader->last_name,
    //                     'amount_payable'=>number_format((float)($payout_data->amount_to_withdraw),2,'.',''),
    //                     'vendor_id' =>request()->vendor_id,
    //                     'advert_id' =>request()->advert_id,
    //                     'order_id'  =>request()->order_id,
    //                     'payout_id' =>$payout->id
    //                 ],200
    //             );
    //         }
    //         else{
    //             //* UPDATE THE ORDER ITEM STATUS TO INITIALIZED
    //             OrderItem::where('order_id',request()->order_id)->where('advert_id',request()->advert_id)->where('payout_status','not_started')->update(['payout_status'=>'initialized']);
    //             return response()->json(
    //                 [
    //                     'status'        =>'success',
    //                     'message'       =>'You have successfully initialized the transfer of GHS.'.number_format((float)(request()->payable),2,'.','').' to selected vendor' ,
    //                     'transfer_code' =>$payout_exists->transfer_id,
    //                     'vendor_name'   =>$vendor->first_name.' '.$vendor->last_name,
    //                     'amount_payable'=>number_format((float)(request()->payable),2,'.',''),
    //                     'vendor_id'     =>request()->vendor_id,
    //                     'advert_id'     =>request()->advert_id,
    //                     'order_id'      =>request()->order_id,
    //                     'payout_id'     =>$payout_exists->id
    //                 ],200
    //             );
    //         }

    //     }catch(\Exception $e){
    //         return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
    //     }
    // }

    //TODO=> ADMIN INDICATE A SUCCESSFUL PAYOUT FOR A CYCLE
    // public function finalize_payout($withdrawal_request){
    //     try{
    //         $user =auth()->guard('api')->user();
    //         if(!$user && ($user->role!=="admin" || $user->role!=="super_admin")){
    //             return response()->json(['status'=>'failed','message'=>'Sorry, only admins and super admins can access this information'],401);
    //         }
    //         $withdraw_request = GroupWithdrawalRequest::where('id',$withdraw_request)->where('status','processing')->first();
    //         if(!$withdraw_request){
    //             return response()->json(['status'=>'failed','message'=>'Sorry, This request is not valid'],401);
    //         }


    //     }catch(\Exception $e){
    //         return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
    //     }
    // }


    //TODO=> RESEND THE OTP FOR THE PAYOUT IN THE EVENT THAT AN OTP IS ACTIVATED IN PAYSTACK FOR THE TRANSFER OF FUNDS
}
