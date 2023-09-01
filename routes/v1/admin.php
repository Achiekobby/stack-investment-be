<?php

use Illuminate\Support\Facades\Route;

//* AuthControllers
use App\Http\Controllers\Auth\AdminAuthenticationController;

Route::group(['prefix'=>'admin'],function(){

    // Tip:: Register a new user as an admin
    Route::post('add_admin',[AdminAuthenticationController::class,'new_admin_register']);

    //Tip:: Login an admin
    Route::post('login',[AdminAuthenticationController::class,'login']);

    //Tip:: Changing user Admin Privileges
    Route::post("change_admin_state/{unique_id}",[AdminAuthenticationController::class,"change_user_admin_status"]);

    //Tip:: reset admin password

    //Tip:: Password code resend for new admin

});
?>
