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
use App\Models\User;

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

    //TODO=> Changing a regular users state to Admin and vice versa
    public function change_user_admin_status($unique_id, Request $request){
        try{

            $rules = ["role"=>"required|string"];
            $validation = Validator::make(request()->all(),$rules);
            if($validation->fails()){
                return response()->json(['status'=>'failed','message'=>$validation->errors()->first()],422);
            }

            $admin = auth()->guard("api")->user();
            if($admin->role !== "super_admin"){
                return response()->json(['status'=>"failed","message"=>"Sorry, only an Admin/Super-Admin can access this functionality"],403);
            }

            //* Extract the users the admin wants to change the status of.
            $user = User::where("uuid",$unique_id)->first();
            $role = $user->role;
            if(!$user){
                return response()->json(['status'=>'failed',"message"=>"User not found"],404);
            }

            if($request->role === "admin"){
                if($user->role === "admin"){
                    return response()->json(['status'=>'failed','message'=>'Sorry, user is already an Admin'],400);
                }
                else{
                    $user->update(['role'=>"admin"]);
                    $message= "";
                    if($role === "regular"){
                        $message = "User successfully made into an Admin";
                    }
                    if($role === "super_admin"){
                        $message = "Successfully reduced User permissions from Super-Admin to Admin";
                    }
                    return response()->json(['status'=>'success','message'=>$message],200);
                }
            }
            if($request->role === "super_admin"){
                if($admin->role !== "super_admin"){
                    return response()->json(['status'=>"failed","message"=>"Sorry, only an Super-Admin can change a Role to a Super Admin"],403);
                }
                else{
                    if($user->role === "super_admin"){
                        return response()->json(['status'=>'failed','message'=>'Sorry, user is already a Super Admin'],400);
                    }
                    else{
                        $user->update(['role'=>"super_admin"]);
                        $message= "";
                        if($role === "regular"){
                            $message = "User successfully made into an Super-Admin";
                        }
                        if($role === "admin"){
                            $message = "Successfully upgraded User permissions from Admin to Super-Admin";
                        }
                        return response()->json(['status'=>'success','message'=>$message],200);
                        // return response()->json(['status'=>'success','message'=>'You have successfully revoked a user\'s Admin privileges!'],200);
                    }
                }
            }
            if($request->role === "regular"){
                if($admin->role !== "super_admin"){
                    return response()->json(['status'=>"failed","message"=>"Sorry, only a Super-Admin can change an User Roles"],403);
                }
                else{
                    if($user->role === "regular"){
                        return response()->json(['status'=>'failed','message'=>'Sorry, user is already a Regular User of the Application'],400);
                    }
                    else{
                        $user->update(['role'=>"regular"]);
                        $message= "";
                        if($role === "super_admin"){
                            $message = "Super-Admin has been made into a Regular User Successfully";
                        }
                        if($role === "admin"){
                            $message = "Admin has been made into a Regular User Successfully";
                        }
                        return response()->json(['status'=>'success','message'=>$message],200);
                    }
                }
            }
        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }
}
