<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//* Request
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\PasswordResetRequest;

//* Models
use App\Models\User;

//* Resources
use App\Http\Resources\User\UserDetailsResource;

//* Notification
use App\Notifications\EmailVerificationNotification;
use App\Notifications\PasswordResetNotification;

//* Utilities
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Keygen\Keygen;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

class UserAuthenticationController extends Controller
{
    //*format the registration input field
    public function format_registration_data(RegisterRequest $request): array{
        $first_name = Str::lower($request->validated()['first_name']);
        $last_name  = Str::lower($request->validated()['last_name']);
        $phone_number=$request->validated()['phone_number'];
        $email      = Str::lower($request->validated()['email']);
        $password   = Hash::make($request->validated()['password']);
        $uuid       = Uuid::uuid4()->toString();
        $email_verification_code =  Keygen::numeric(6)->generate();

        return [
            'first_name'                =>$first_name,
            'last_name'                 =>$last_name,
            'phone_number'              =>$phone_number,
            'uuid'                      =>Uuid::uuid4()->toString(),
            'email'                     =>$email,
            'password'                  =>$password,
            'email_verification_code'   =>$email_verification_code,
            'remember_token'            =>Str::random(10),
        ];
    }

    //TODO :: Register a new user
    public function register(RegisterRequest $request){
        try{
            if(User::where('email',$request->email)->first()){
                return response()->json(['status'=>'failed','message'=>'Email is already Taken'],403);
            }

            if(User::where('phone_number',$request->email)->first()){
                return response()->json(['status'=>'failed','message'=>'Phone Number is already Taken'],403);
            }

            $user = User::query()->create($this->format_registration_data($request));
            if($user){
                 //* Generating the access token
                $access_token = $user->createToken('stack_user_access_token')->accessToken;

                //* send email verification code to user email
                $email_already_verified = User::query()->where([['email',$user->email],['email_verified_at','!=',null]])->first();
                if($email_already_verified){
                    $user->update(['email_verified_at'=>Carbon::now()->format('Y-m-d H:i:s')]);
                    $user->save();
                }
                else{
                    //* send email verification code to the registered user.
                    $verification_message = [
                        'name'      =>$user->first_name." ".$user->last_name,
                        'user_id'   =>$user->id,
                        'email'     =>$user->email,
                        'code'      =>$user->email_verification_code,
                    ];
                    $user->notify(new EmailVerificationNotification($verification_message));
                }
                return response()->json(
                    [
                        'status'        =>'success',
                        'message'       =>'Welcome. You have now successfully signed up for Stacks-Investment-Hub',
                        "user"          =>new UserDetailsResource(User::where('id',$user->id)->first()),
                        "access_token"  =>$access_token
                    ],
                201);
            }
            return response()->json(
                [
                    'status'=>'failed',
                    'message'=>'Sorry, registration encountered a problem. please try again later'
                ],
            400);
        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO => Verify User Email
    public function verify_user_email(Request $request){
        try{
            $rules = [
                'code'=>'required'
            ];
            $validation = Validator::make($request->all(),$rules);
            if($validation->fails()){
                return response()->json(['status'=>'failed','message'=>$validation->errors()->first()],422);
            }

            //* extract user using the token
            $user = auth()->guard('api')->user();
            if(!$user){
                return response()->json(['status'=>'failed','message'=>'User not found!'],404);
            }
            $user_code = $user->email_verification_code;
            if($request->code !== $user_code){
                return response()->json(['status'=>'failed','message'=>'Verification code is invalid!!'],400);
            }
            if($user->email_verified_at){
                return response()->json(['status'=>'failed','message'=>'Your email has already been verified!!'],400);
            }
            $user->update(['email_verified_at'=>Carbon::now()->format('Y-m-d H:i:s'),'email_verification_code'=>null]);
            $token = $user->createToken('stack_user_access_token')->accessToken;
            return response()->json([
                'status'    =>'success',
                'message'   =>'You have successfully verified your email',
                'user'      =>new UserDetailsResource($user),
                'token'     =>$token
            ],200);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO => Resend email verification code
    public function resendCode(){
        $user =auth()->guard('api')->user();
        try{
            $code = Keygen::numeric(4)->generate();
            $user->update(['email_verification_code'=>$code]);
            $user->save();

            if($user){
                //* send the notification email to the user
                $verification_message = [
                    'name'      =>$user->first_name." ".$user->last_name,
                    'user_id'   =>$user->id,
                    'email'     =>$user->email,
                    'code'      =>$code,
                ];
                $user->notify(new EmailVerificationNotification($verification_message));
                return response()->json(
                    [
                        'status'    =>'success',
                        'message'   =>'Your verification code has been sent to your email.'
                    ],
                200);
            }
            return response()->json(['status'=>'failed','message'=>"User not found"],404);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO => LOGIN A USER
    public function login(){
        try{
            $rules = [
                "email"     =>"required|email",
                "password"  =>'required|string|min:8'
            ];

            $validation = Validator::make(request()->all(), $rules);
            if($validation->fails()){
                return response()->json(['status'=>'failed','message'=>$validation->errors()->all()],422);
            }

            $user = User::query()->where('email',request()->email)->first();
            if($user){
                $password_verification = Hash::check(request()->password,$user->password);
                if($password_verification){
                    $access_token = $user->createToken("spark_hub_user_login_token")->accessToken;
                    return response()->json(
                        [
                            'status'    => 'success',
                            'message'   => "You have successfully signed in to your account",
                            "user"      => new UserDetailsResource($user),
                            "token"     => $access_token
                        ],
                    200);
                }
                return response()->json(['status'=>'failed','message'=>"Incorrect Credentials"],403);

            }
            return response()->json(['status'=>'failed','message'=>"User not found"],404);
        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO => Password Reset Email Check
    public function resetPasswordEmailCheck(){
        try{
            $email = request()->email;
            $password_reset_code = Keygen::numeric(4)->generate();

            $rule =['email'=>'required|email|string'];
            $validation = Validator::make(request()->all(), $rule);
            if($validation->fails()){
                return response()->json(['status'=>'failed','message'=>$validation->errors()->all()],422);
            }

            $user = User::query()->where('email',$email)->first();
            if($user){
                $user->update(['password_reset_code'=>$password_reset_code]);
                $user->save();

                //* send the notification email to the user
                $password_reset_data = [
                    'name'      =>$user->first_name." ".$user->last_name,
                    'user_id'   =>$user->id,
                    'email'     =>$user->email,
                    'code'      =>$password_reset_code,
                ];
                $user->notify(new PasswordResetNotification($password_reset_data));

                return response()->json(
                    [
                        'status'=>'success',
                        'message'                =>'Your password reset code has been sent to your email.',
                        "user_email"             =>$user->email,
                        ]
                ,200);
            }
            return response()->json(['status'=>'failed','message'=>"User not found"],404);
        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO => RESET USER PASSWORD
    public function resetPassword(PasswordResetRequest $request){
        try{
            //* Payload
            $email                  = $request->validated()['email'];
            $new_password           = $request->validated()['password'];
            $password_reset_code    = $request->validated()['password_reset_code'];

            $user =User::query()->where([['email',$email],['password_reset_code', $password_reset_code]])->first();
            if($user){
                $update_password = $user->update([
                    'password'=>Hash::make($new_password),
                    'password_reset_code'=>null
                ]);
                $user->save();

                //* access token
                $user_access_token = $user->createToken("user_password_reset_access_token")->accessToken;

                if($update_password){
                    return response()->json(
                        [
                            'status'        =>'success',
                            'message'       =>'You have successfully updated your password',
                            'user'          => new UserDetailsResource($user),
                            'access_token'  =>$user_access_token
                        ],
                    200);
                }
                return response()->json(['status'=>'failed','message'=>'Sorry, Password reset failed'],400);
            }
            return response()->json(['status'=>'failed','message'=>"User email or password reset code may be wrong. Please check and try again"],404);
        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }

    //TODO => RESEND PASSWORD RESET CODE
    public function resendPasswordResetCode(){
        $rule =['email'=>'required|email|string'];
        $validation = Validator::make(request()->all(), $rule);
        if($validation->fails()){
            return response()->json(['status'=>'failed','message'=>$validation->errors()->all()],422);
        }
        $user =User::where('email',request()->email)->first();
        try{
            $code = Keygen::numeric(4)->generate();
            $user->update(['password_reset_code'=>$code]);
            $user->save();

            if($user){
                //* send the notification email to the user
                $password_reset_code = [
                    'name'      =>$user->first_name." ".$user->last_name,
                    'user_id'   =>$user->id,
                    'email'     =>$user->email,
                    'code'      =>$code,
                ];
                $user->notify(new PasswordResetNotification($password_reset_code));
                return response()->json(
                    [
                        'status'    =>'success',
                        'message'   =>'Your password reset code has been sent to your email.',
                        'code'      =>$code
                    ],
                200);
            }
            return response()->json(['status'=>'failed','message'=>"User not found"],404);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed','message'=>$e->getMessage()],500);
        }
    }
}
