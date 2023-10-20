<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\General\OrganizationController;


Route::group(["prefix"=>"user/group/"], function(){

    //Tip:: Create a new organization
    Route::post("create",[OrganizationController::class,"create_organization"]);

    //Tip:: Extract all groups a user has created
    Route::get("all",[OrganizationController::class,"extract_user_group"]);

    //Tip:: Send invite to a group
    Route::post("invite",[OrganizationController::class,"invite_user_to_group"]);

    //Tip:: extract all pending invites for a logged in user
    Route::get('pending/invites',[OrganizationController::class,'extract_all_user_invites']);

    //Tip:: Handle the invitation by the invited user
    Route::post('handle/invite',[OrganizationController::class,'handle_invitation']);

    //Tip:: Search through registered users for people the team leader might want to include in an organization.
    Route::get("search_users",[OrganizationController::class,"search_registered_users"]);

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

    //Tip:: Add new Member to the Organization.
    Route::post("member/add/{unique_id}",[OrganizationController::class,"add_members_to_organization"]);

});
