<?php

use Illuminate\Support\Facades\Route;

use  App\Http\Controllers\General\SettingsController;

Route::group(['prefix'=>'general'],function(){

    //Tip:: Show All category for crowdfunding projects
    Route::get('category/all',[SettingsController::class,'categories']);

    //Tip:: Show category for crowdfunding projects
    Route::get('category/{id}',[SettingsController::class,'category']);

});
?>
