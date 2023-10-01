<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//* Models
use App\Models\ProjectPayment;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

//* Notifications
use App\Notifications\Crowdfunding\DonationReceiptNotification;
use App\Notifications\Crowdfunding\DonorRecipientNotification;

//* Utilities
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;

class PaystackWebhookController extends Controller
{
    public function handle(Request $request){
        $input = @file_get_contents("php://input");
        $event = json_decode($input);
        file_put_contents("paystack.txt",$input);

        if($event->event === "charge.success"){
            if($event->data->metadata->payment_type === "campaign_donation"){
                $payment_extra_data = $event->data->metadata;
                $project_payment = ProjectPayment::query()->where("id",$payment_extra_data->payment_id)->first();

                //* updating the crowdfunding campaign entry
                $crowdfunding_project = Project::query()->where("unique_id",$payment_extra_data->project_uuid)->first();
                $crowdfunding_project->update([
                    "amount_received"   =>number_format((float)($crowdfunding_project->amount_received + ($event->data->amount/100)),2,'.',''),
                    "number_of_donors"  => $crowdfunding_project->number_of_donors +1
                ]);

                //* check to see if the target has been reached
                if($crowdfunding_project->mode_of_completion === "target_achieved"){
                    if($crowdfunding_project->amount_received >= $crowdfunding_project->amount_requested){
                        //* update the status of the campaign
                        $crowdfunding_project->update([
                            "project_status"=>"completed"
                        ]);
                    }
                }

                //* project payment update
                $payment_update = $project_payment->update([
                    "donation_reference"=>$event->data->reference,
                    "medium_of_payment"=>$event->data->authorization->channel,
                    "payment_status"=>"paid",
                    "paid_at"=>date("Y-m-d H:i:s", strtotime($event->data->paid_at))
                ]);

                //* Notification Receipt sent to the person who made the donation
                $mail_data = [
                    "name"              =>Str::title($project_payment->first_name)." ".Str::title($project_payment->last_name),
                    "email"             =>$project_payment->email,
                    "title"             =>Str::title($crowdfunding_project->title),
                    "amount"            =>number_format((float)($event->data->amount/100),2,'.',''),
                    "payment_method"    =>$event->data->authorization->channel,
                    "date"              =>date("Y-m-d", strtotime($event->data->paid_at))
                ];
                Notification::route("mail", $project_payment->email)->notify(new DonationReceiptNotification($mail_data));

                //* Notification Receipt sent to the person who created the campaign
                $user = User::where('id',$crowdfunding_project->user_id)->first();
                $data = [
                    "name"              =>Str::title($user->first_name)." ".Str::title($user->last_name),
                    "donor_name"        =>Str::title($project_payment->first_name)." ".Str::title($project_payment->last_name),
                    "email"             =>$project_payment->email,
                    "title"             =>Str::title($crowdfunding_project->title),
                    "amount"            =>number_format((float)($event->data->amount/100),2,'.',''),
                    "payment_method"    =>$event->data->authorization->channel,
                    "date"              =>date("Y-m-d", strtotime($event->data->paid_at))
                ];
                $user->notify(new DonorRecipientNotification($data));
            }
        }
    }

    public function callback(){
        return redirect("https://stacksinvestment.com/");
    }
}
