<?php

use Illuminate\Support\Facades\Route;

use  App\Http\Controllers\General\CrowdfundingTransactionController;

Route::group(['prefix'=>'user/payment'],function(){

    //Tip:: Show All category for crowdfunding projects
    Route::post('project/donate',[CrowdfundingTransactionController::class,'make_donation']);

});
?>
