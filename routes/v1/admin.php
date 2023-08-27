<?php

use Illuminate\Support\Facades\Route;

//* AuthControllers
use App\Http\Controllers\Auth\AdminAuthenticationController;

Route::group(['prefix'=>'admin'],function(){

    // Tip:: Register a new user as an admin
    Route::post('add_admin',[AdminAuthenticationController::class,'new_admin_register']);

    //Tip:: Login an admin
    Route::post('login',[AdminAuthenticationController::class,'login']);

    //Tip:: reset admin password

    //Tip:: Password code resend for new admin

});
?>
