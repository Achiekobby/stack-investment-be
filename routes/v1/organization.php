<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\General\OrganizationController;


Route::group(["prefix"=>"user/organization/"], function(){

    //Tip:: Create a new organization
    Route::post("create",[OrganizationController::class,"create_organization"]);

    //Tip:: Show all the organization a user is part of regardless of the status of the organization
    Route::get("show/all",[OrganizationController::class,"index"]);

    //Tip:: Show all the organization of a user which are active
    Route::get("active/all",[OrganizationController::class,"active_organizations"]);

    //Tip:: Show details about a specific organization
    Route::get("{unique_id}",[OrganizationController::class,"show_organization"]);

    //Tip:: Update a specific organization
    Route::patch("edit/{unique_id}",[OrganizationController::class,"update_organization"]);

    //Tip:: Update a specific organization
    Route::delete("remove/{unique_id}",[OrganizationController::class,"remove"]);

});
