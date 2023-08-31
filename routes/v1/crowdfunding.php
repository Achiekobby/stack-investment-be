<?php

use Illuminate\Support\Facades\Route;

//* Controllers
use App\Http\Controllers\General\CrowdFundingController;

Route::group(["prefix"=>"user/project"], function(){

    //Tip:: Creating a new project to solicit for funding
    Route::post("create",[CrowdFundingController::class,"create_project"]);

    //Tip:: Update a project
    Route::patch('update/{unique_id}',[CrowdFundingController::class,"update"]);

    //Tip:: Show a specific project created by a user
    Route::get('show/{unique_id}',[CrowdFundingController::class,"show"]);

    //Tip:: Show all the projects created by a user
    Route::get('all',[CrowdFundingController::class,"index"]);

    //Tip:: Remove a project created by a user
    Route::delete('remove/{unique_id}',[CrowdFundingController::class,"remove"]);

    //Tip:: Extract all the projects which are open for donation
    Route::get("open",[CrowdFundingController::class,"all_open_projects"]);
});
