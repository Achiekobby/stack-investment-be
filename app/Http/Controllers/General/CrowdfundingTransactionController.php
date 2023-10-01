<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//* Models
use App\Models\Project;
use App\Models\Subaccount;
use App\Models\ProjectPayment;

//* Resources

//* Utilities
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Keygen\Keygen;

class CrowdfundingTransactionController extends Controller
{
    //TODO => Making a Donation towards a crowdfunding campaign
    public function make_donation(){
        try{

            $rules =  [
                'first_name'=>'required|string',
                'last_name' =>'required|string',
                'email'     =>'required|email',
                "amount"    =>"required",
                "project_uuid"=>"required|string",
            ];

            //!Validation
            $validation = Validator::make(request()->all(), $rules);
            if($validation->fails()){
                return response()->json(['status'=>'failed','message'=>$validation->errors()->first()],422);
            }

            //* Finding the project the user is trying to donate for.
            $project = Project::query()->where([
                ['unique_id',request()->project_uuid],
                ['approval','approved'],
                ['project_status','active']
            ])->first();

            if(!$project){
                return response()->json(['status'=>'failed','message'=>'Crowdfunding campaign is no longer active'],404);
            }

            //* formatting the amount inserted
            $donated_amount = number_format((float)(request()->amount), 2,'.','');
            if($donated_amount <=0.00 || $donated_amount <=0){
                return response()->json(['status'=>'failed','message'=>'Sorry, Amount Entered is Invalid'],400);
            }

            //* Recording payment attempt in the database
            $project_donation_entry = $project->project_payments()->create([
                "unique_id"         =>Str::uuid(),
                "user_id"           =>null,
                "first_name"        =>request()->first_name,
                "last_name"         =>request()->last_name,
                "email"             =>request()->email,
                "amount_donated"    =>$donated_amount,
                "donation_id"       =>Keygen::numeric(6)->prefix("DON-")->generate(),
                "donation_reference"=>null,
                "medium_of_payment" =>"mobile_money",
                "payment_status"    =>"unpaid",
                "paid_at"           =>null,
            ]);

            //* Payment Data
            $secret_key  = config('services.paystack.secret_key');
            $payment_url = config('services.payment_url.paystack');
            $data = [
                'email'     =>request()->email,
                'amount'    =>$donated_amount*100,
                'metadata'  =>json_encode($array=[
                    'payment_type'=>"campaign_donation",
                    'project_uuid'  =>$project->unique_id,
                    'donated_by'    =>Str::title(request()->first_name)." ".Str::title(request()->last_name),
                    'payment_id'    =>$project_donation_entry->id,
                ])
            ];

            //* Making the payment with paystack
            $payment = Http::withToken($secret_key)->post((string)$payment_url."/transaction/initialize", $data)->json();

            return response()->json($payment,200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }
}
