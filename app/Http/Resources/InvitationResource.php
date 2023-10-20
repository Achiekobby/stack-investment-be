<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\InvitationGroupDetailsResource;

class InvitationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "uuid"          =>$this->uuid,
            "user_name"     =>$this->user_name,
            "email"         =>$this->email,
            "user_phone"    =>$this->user_phone,
            "group_details" =>new InvitationGroupDetailsResource($this->organization)
        ];
    }
}
