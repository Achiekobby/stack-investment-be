<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//* Request
use App\Http\Requests\Admin\AdminRequest;

//* Resources
use App\Http\Resources\Admin\AdminDetailsResource;

//* Models
use App\Models\Admin;

//* Notification
use App\Notifications\Admin\NewAdminNotification;

//* Utilities
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Keygen\Keygen;
use Ramsey\Uuid\Uuid;

class AdminAuthenticationController extends Controller
{
    //TODO => New Admin Registration
    public function new_admin_register(AdminRequest $request){
        try{
            $first_name     = $request->validated()['first_name'];
            $last_name     = $request->validated()['last_name'];
            $email          = $request->validated()['email'];
            $phone_number   = $request->validated()['phone_number'];
            $role           = $request->validated()['role'];

            $super_admin = auth()->guard('admin')->user();
            if($super_admin->role !=="super_admin"){
                return response()->json(['status'=>'failed','message'=>'Sorry, only a super admin can add new admins/super-admins'],400);
            }

            //* Generic password for the admin or super-admin
            $generic_password = Keygen::numeric(6)->prefix('ADM-'.time())->generate();

            //* creating the admin
            $new_admin = Admin::query()->create([
                'first_name'    =>$first_name,
                'last_name'     =>$last_name,
                'full_name'     =>$first_name." ".$last_name,
                'email'         =>$email,
                'role'          =>$role,
                'phone_number'  =>$phone_number,
                'uuid'          =>Uuid::uuid4()->toString(),
                'password'      =>Hash::make($generic_password)
            ]);
            if($new_admin){
                $mail_details =[
                    'name'      =>request()->first_name." ".request()->last_name,
                    'email'     =>request()->email,
                    'password'  =>$generic_password,
                    'role'      =>$request->role === "admin" ? "Admin" : "Super-Admin",
                ];
                $new_admin->notify(new NewAdminNotification($mail_details));

                return response()->json(['status'=>'success','message'=>'You have successfully added a new User with '.request()->role.' privileges'],201);
            }
        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO => Admin login
    public function login(Request $request){
        try{
            $rules = [
                'email'=>'required|email',
                'password'=>'required|string|min:8',
            ];
            $validation = Validator::make($request->all(),$rules);
            if($validation->fails()){
                return response()->json(['status'=>'failed','message'=>$validation->errors()->first()],422);
            }

            $admin = Admin::query()->where('email',$request->email)->first();
            if(!$admin){
                return response()->json(['status'=>'failed','message'=>'Sorry, Your email was not related to any admin account'],404);
            }
            //* Password Hash Check
            $password_verification = Hash::check($request->password, $admin->password);
            if($password_verification){
                $accessToken = $admin->createToken('admin_login_access_token')->accessToken;
                return response()->json(
                    [
                        'status'    =>'success',
                        'message'   =>'You have successfully signed in',
                        'admin'     =>new AdminDetailsResource($admin),
                        'token'     =>$accessToken
                    ]
                );
            }
            return response()->json(['status'=>'failed','message'=>'Sorry, Wrong Credentials'],400);
        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }
}
