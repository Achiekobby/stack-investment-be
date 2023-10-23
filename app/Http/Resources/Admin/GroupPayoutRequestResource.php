<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

//* Resources
use App\Http\Resources\General\OrganizationResource;
use App\Http\Resources\General\PayoutResource;

//* Utilities
use Carbon\Carbon;
use Illuminate\Support\Str;

//* Models
use App\Models\User;
use App\Models\Organization;

class GroupPayoutRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $team_leader = User::query()->where("id",$this->user_id)->first();
        $group = Organization::query()->where("id",$this->organization_id)->first();
        return [
            "id"=>$this->id,
            "group_leader"      =>Str::title($this->group_admin_name),
            "group_leader_email"=>$team_leader->email,
            "group_name"        =>Str::title($group->title),
            "amount_to_withdraw"=>$this->amount_to_withdraw,
            "group_cycle_number"=>$this->cycle_number,
            "payout_method"     =>new PayoutResource($team_leader->payout_methods()->where('status','active')->first()),
            "withdrawal_status" =>$this->status,
            "created_at"        =>Carbon::createFromFormat("Y-m-d H:i:s",$this->created_at)->format("Y-m-d")
        ];
    }
}
