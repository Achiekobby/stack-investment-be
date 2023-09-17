<?php

use Illuminate\Support\Facades\Route;

//* AuthControllers
use App\Http\Controllers\Auth\AdminAuthenticationController;

//* AdminControllers
use App\Http\Controllers\Admin\AdminCrowdfundingOperationsController;
use App\Http\Controllers\Admin\AdminOrganizationController;
use App\Http\Controllers\Admin\AdminSettingsController;



Route::group(['prefix'=>'admin'],function(){

    // Tip:: Register a new user as an admin
    Route::post('add_admin',[AdminAuthenticationController::class,'new_admin_register']);

    //Tip:: Login an admin
    Route::post('login',[AdminAuthenticationController::class,'login']);

    //Tip:: Changing user Admin Privileges
    Route::post("change_admin_state/{unique_id}",[AdminAuthenticationController::class,"change_user_admin_status"]);

    //Tip:: reset admin password

    //Tip:: Password code resend for new

    //Tip:: List all the crowdfunding projects
    Route::get("projects/all",[AdminCrowdfundingOperationsController::class,"list_projects"]);

    //Tip:: Approving crowdfunding project
    Route::get("approve/project/{unique_id}",[AdminCrowdfundingOperationsController::class, 'approve_project']);

    //Tip:: Rejecting crowdfunding project
    Route::post("reject/project",[AdminCrowdfundingOperationsController::class, 'reject_project']);

    //Tip:: List all the organization projects
    Route::get("organizations/all",[AdminOrganizationController::class,"list_organizations"]);

    //Tip:: Approving organization project
    Route::get("approve/organization/{unique_id}",[AdminOrganizationController::class, 'approve_organization']);

    //Tip:: Rejecting organization project
    Route::post("reject/organization",[AdminOrganizationController::class, 'reject_organization']);


    //?INFORMATION => THE FOLLOWING API HAS TO WITH THE ADMIN SETTINGS
    //Tip:: Add new category for crowdfunding projects
    Route::post('category/add',[AdminSettingsController::class,'add_category']);

    //Tip:: Update category for crowdfunding projects
    Route::patch('category/update/{id}',[AdminSettingsController::class,'update_category']);

    //Tip:: Remove category for crowdfunding projects
    Route::delete('category/remove/{id}',[AdminSettingsController::class,'delete_category']);

    //Tip:: Show All category for crowdfunding projects
    Route::get('category/all',[AdminSettingsController::class,'show_all_category']);

    //Tip:: Show category for crowdfunding projects
    Route::get('category/{id}',[AdminSettingsController::class,'show_category']);
});
?>
