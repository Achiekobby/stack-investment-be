<?php

use Illuminate\Support\Facades\Route;

use  App\Http\Controllers\General\CrowdfundingTransactionController;
use  App\Http\Controllers\General\P2PTransactionController;

Route::group(['prefix'=>'user/payment'],function(){

    //Tip:: Show All category for crowdfunding projects
    Route::post('project/donate',[CrowdfundingTransactionController::class,'make_donation']);

    //Tip:: Contribute to a scheme
    Route::post("group/contribute",[P2PTransactionController::class,"member_contribute"]);

    //Tip:: Make withdrawal request
    Route::get("make/withdrawal/request/{group_uuid}",[P2PTransactionController::class, "withdrawal_request"]);

    //Tip:: Extract all the contributions made for a specific group
    Route::get("group/contributions/{group_uuid}",[P2PTransactionController::class,"list_all_group_contributions"]);

    //Tip:: Extract all the contributions a user has made for a specified group
    Route::get("group/contribution/list/{group_uuid}",[P2PTransactionController::class,"user_contributions"]);

});
?>
