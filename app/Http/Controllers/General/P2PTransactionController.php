<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//* Models
use App\Models\ContributionCycle;
use App\Models\Organization;

//* Resources
use App\Http\Resources\General\ContributionResource;

//* Utilities
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class P2PTransactionController extends Controller
{
    //TODO=> MEMBER OF A GROUP MAKING A CONTRIBUTION FOR THE SPECIFIC DURATION FOR MATURITY OF THE GROUP CONTRIBUTION
    public function member_contribute(){
        try{
            //* validation of request body
            $rules = [
                "amount"=>"required",
                "group_uuid"=>'required|string',
            ];
            $validation = Validator::make(request()->all(),$rules);
            if($validation->fails()){
                return response()->json(['status'=>'failed','message'=>$validation->errors()->first()],422);
            }

            //* extract the details of the logged in user
            $user = auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>'Sorry, User not found !!'],404);
            }

            //* find the organization the user is making the contributions for
            $group = Organization::query()->where('unique_id',request()->group_uuid)->where('status','active')->where('approval','approved')->first();
            if(!$group){
                return response()->json(['status'=>'failed','message'=>'Group not found!'],404);
            }

            //* checking if the logged in user is a member of the group
            $is_user_member = $group->members()->where('user_id',$user->id)->first();
            if(!$is_user_member){
                return response()->json(['status'=>'failed','message'=>'Sorry, you must be a member of this group to contribute'],400);
            }

            //* create an entry for the contribution being made
            $contribution_cycle = ContributionCycle::query()->where('organization_id',$group->id)->where('payment_status','unpaid')->first();

            //* proceed with the payment
            $new_contribution = $contribution_cycle->contributions()->create([
                'user_id'=>$user->id,
                'amount'=>number_format((float)(request()->amount),2,'.',''),
            ]);

            //* proceed with the payment using paystack\
            $contribution_amount = number_format((float)(request()->amount),2,'.','');

            //* Payment Data
            $secret_key  = config('services.paystack.secret_key');
            $payment_url = config('services.payment_url.paystack');
            $data = [
                'email'     =>$user->email,
                'amount'    =>$contribution_amount*100,
                'metadata'  =>json_encode($array=[
                    'payment_type'      =>"scheme_contribution",
                    'group_uuid'        =>$group->unique_id,
                    'contributed_by'    =>Str::title($user->first_name)." ".Str::title($user->last_name),
                    'contribution_id'   =>$new_contribution->id,
                    'user_id'           =>$user->id,
                    "organization_title"=>$group->title
                ])
            ];

            //* Making the payment with paystack
            $payment = Http::withToken($secret_key)->post((string)$payment_url."/transaction/initialize", $data)->json();

            return response()->json($payment,200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO=> LISTING ALL THE CONTRIBUTIONS OF A GROUP ONLY ACCESSIBLE TO THE GROUP ADMIN
    public function list_all_group_contributions($group_uuid){
        try{
            //* extract the details of the logged in user
            $user = auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>'Sorry, User not found !!'],404);
            }

            $group =  Organization::query()->where('unique_id',$group_uuid)->first();
            if(!$group){
                return response()->json(['status'=>'failed','message'=>'Sorry, Group not found!!'],404);
            }

            //* checking to see if the group admin is the currently logged in user
            $is_group_admin = $group->members()->where('user_id',$user->id)->first();
            if(!$is_group_admin || $is_group_admin->role !=="group_admin"){
                return response()->json(['status'=>'failed','message'=>'You must be a member and group admin of this group to view this data!!'],400);
            }

            //* extracting all the contributions made for this group
            $contributions = ContributionCycle::join("contributions","contributions.contribution_cycle_id","=","contribution_cycles.id")
                                                ->where("contribution_cycles.organization_id","=",$group->id)
                                                ->where('contributions.payment_status',"paid")
                                                ->select(
                                                    "contributions.id",
                                                    "contributions.amount",
                                                    "contributions.payment_status",
                                                    "contributions.user_id",
                                                    "contributions.amount",
                                                    "contributions.payment_status",
                                                    "contributions.payment_date",
                                                    "contribution_cycles.organization_id",
                                                    "contribution_cycles.cycle_number"
                                                )->get();
            return response()->json(['status'=>'success','all_contributions'=>ContributionResource::collection($contributions)],200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO=> LISTING ALL THE CONTRIBUTIONS MADE MY ANY MEMBER OF THE GROUP. THIS IS ACCESSIBLE TO BOTH ADMIN AND OTHER MEMBERS OF THE GROUP

    //TODO=> MAKING A WITHDRAWAL REQUEST TO THE ADMIN

    //TODO=> GROUP ADMIN ADDING A PAYOUT METHOD(PREFERABLY A MOMO NUMBER) WHICH WILL BE ASSOCIATED TO THE GROUP ADMIN ACCOUNT.

    //TODO=> COMPUTING THE TOTAL PAYOUT FOR LOGGED IN USER FOR A GROUP THEY JOIN AND THE TOTAL ARREARS IF ANY

}
