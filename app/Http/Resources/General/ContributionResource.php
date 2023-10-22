<?php

namespace App\Http\Resources\General;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

//* Models
use App\Models\User;
use App\Models\Organization;

class ContributionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $organization = Organization::query()->where('id',$this->organization_id)->first();
        $contribution_name = User::where("id",$this->user_id)->first();
        return [
            "id"=>$this->id,
            "group_name"        =>$organization->title,
            "group_unique_id"   =>$organization->unique_id,
            "amount"            =>$this->amount,
            "payment_status"    =>$this->payment_status,
            "payment_date"      =>$this->payment_date,
            "currency"          =>"GHS",
            "cycle_number"      =>$this->cycle_number
        ];
    }
}
