<?php

use Illuminate\Support\Facades\Route;

//* AuthControllers
use  App\Http\Controllers\Auth\UserAuthenticationController;

//* User Operations Controller
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\UserController;

Route::group(['prefix'=>'user'],function(){

    // Tip:: Register a user
    Route::post('register',[UserAuthenticationController::class,'register']);

    //Tip:: Login a user
    Route::post('login',[UserAuthenticationController::class,'login']);

    //Tip:: reset password
    Route::post('reset/password',[UserAuthenticationController::class,'resetPassword']);

    //Tip:: Password code resend
    Route::post('resend/password_reset_code',[UserAuthenticationController::class,'resendPasswordResetCode']);

    //Tip:: password reset code email notification
    Route::post('reset/password/email_check',[UserAuthenticationController::class,'resetPasswordEmailCheck']);

    //Tip:: Verify Email
    Route::post('verify_email',[UserAuthenticationController::class,'verify_user_email']);

    //Tip:: Email verification code resend
    Route::get('resend_email_code',[UserAuthenticationController::class,'resendCode']);

    //Tip:: Add a payment method
    Route::post("payment_method/create",[UserProfileController::class,"create_payment_method"]);

    //Tip:: Making a withdrawal request
    Route::post("withdrawal/request/create",[UserController::class,"make_withdrawal_request"]);

    //Tip :: Extract all user withdrawal request
    Route::get("withdrawal/request/all",[UserController::class,"all_withdrawal_request"]);

    //Tip::Update a pending or denied withdrawal request
    Route::patch("withdrawal/request/update/{id}",[UserController::class,"update_withdrawal_request"]);

});
?>
