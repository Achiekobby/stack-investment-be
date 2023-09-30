<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


//* Models
use App\Models\User;

//* Requests
use App\Http\Requests\User\PaymentMethodRequest;

//* Resources
use App\Http\Resources\User\PaymentMethodResource;

class UserProfileController extends Controller
{
    //TODO => Create a new payment method for the user
    public function create_payment_method(PaymentMethodRequest $req){
        try{
            $account_name = $req->validated()["account_name"];
            $account_type = $req->validated()["account_type"];
            $account_number = $req->validated()["account_number"];
            $momo_number = $req->validated()["momo_number"];
            $momo_network = $req->validated()["momo_network"];
            $bank_name = $req->validated()["bank_name"];

            //* extract user details
            $user = auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>'User not found !!'],404);
            }

            //* setting all previous payment_methods for the user to inactive
            $user_payment_methods = $user->payment_methods;
            if(count($user->payment_methods)>0){

                //Tip:: update the user payment methods
                foreach($user->payment_methods as $payment_method){
                    $payment_method->update([
                        "status"=>"inactive"
                    ]);
                }
            }

            //* create new account entry for the user
            $new_payment_method;
            if($req->validated()['account_type']==="bank"){
                $new_payment_method = $user->payment_methods()->create([
                    "account_number"=>$req->validated()['account_number'],
                    "bank_name"=>$req->validated()['bank_name'],
                    "account_type"=>$req->validated()['account_type'],
                    "account_name"=>$req->validated()['account_name'],
                    "status"=>"active"
                ]);
            }

            if($req->validated()['account_type']==="momo"){
                $new_payment_method = $user->payment_methods()->create([
                    "account_name"=>$req->validated()['account_name'],
                    "momo_number"=>$req->validated()['momo_number'],
                    "momo_network"=>$req->validated()['momo_network'],
                    "account_type"=>$req->validated()['account_type'],
                    "status"=>"active"
                ]);
            }

            return response()->json(['status'=>'success','message'=>'Success, Payment', 'payment_method'=>$new_payment_method],200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }
}
