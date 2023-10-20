<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class InvitationGroupDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "organization_uuid" =>$this->unique_id,
            "title"             =>$this->title,
            "description"       =>$this->description,
            "maturity_period"   =>$this->cycle_period,
            "invitation_link"   =>$this->link,
            "status"            =>$this->status,
            "created_at"        =>Carbon::createFromFormat('Y-m-d H:i:s',$this->created_at)->format('Y-m-d')
        ];
    }
}
