<?php

use Illuminate\Support\Facades\Route;

//* AuthControllers
use  App\Http\Controllers\Auth\UserAuthenticationController;

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

});
?>
