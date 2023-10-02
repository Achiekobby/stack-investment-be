<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

//* Models
use App\Models\User;

use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminWithdrawalRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $project_owner = User::query()->where('id',$this->user_id)->first();
        return [
            "project_owner"=>Str::title($project_owner->first_name)." ".Str::title($project_owner->last_name),
            "project_name"=>Str::title($this->project_name),
            'project_uuid'=>$this->project_uuid,
            'user_uuid'=>$this->user_uuid,
            'amount'=>$this->amount_to_be_withdrawn,
            'currency'=>"GHS",
            "approval_status"=>$this->approval_status,
            "payout_status"=>$this->payout_status,
            "date_of_request"   =>Carbon::createFromFormat('Y-m-d H:i:s',$this->created_at)->format('Y-m-d'),
        ];
    }
}
