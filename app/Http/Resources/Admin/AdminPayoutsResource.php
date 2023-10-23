<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

//* Resources
use App\Http\Resources\Admin\GroupPayoutRequestResource;

//* Models
use App\Models\User;
use App\Models\GroupWithdrawalRequest;

//* Utilities
use Carbon\Carbon;
use Illuminate\Support\Str;

class AdminPayoutsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $admin = User::query()->where("id",$this->admin_id)->first();
        $withdrawal_request = GroupWithdrawalRequest::where("id",$this->withdrawal_request_id)->first();
        return [
            "uuid"              =>$this->uuid,
            "admin_name"        =>Str::title($admin->first_name." ".$admin->last_name),
            "withdrawal_request"=>new GroupPayoutRequestResource($withdrawal_request),
            "recipient_id"      =>$this->recipient_id,
            "recipient_name"    =>Str::title($this->user($this->recipient_id)->first_name." ".$this->user($this->recipient_id)->last_name),
            "payout_type"       =>is_null($this->transfer_code) ? "manual" : "automated",
            "amount"            =>$this->amount_payable,
            'status'            =>$this->status,
            'paid_at'           =>Carbon::createFromFormat("Y-m-d H:i:s",$this->created_at)->format('Y-m-d'),
        ];
    }

    public function user($id){
        return User::query()->where('id',$id)->first();
    }
}
