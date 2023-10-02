<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Str;
use Carbon\Carbon;

class WithdrawalRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "project_uuid"      =>$this->project_uuid,
            "project_name"      =>Str::title($this->project_name),
            "amount_to_withdrawn"=>$this->amount_to_be_withdrawn,
            "currency"          =>"GHS",
            "approval_status"   =>$this->approval_status,
            "payout_status"     =>$this->payout_status,
            "reason_of_rejection"=>$this->reason_of_rejection,
            "date_of_request"   =>Carbon::createFromFormat('Y-m-d H:i:s',$this->created_at)->format('Y-m-d'),
        ];
    }
}
