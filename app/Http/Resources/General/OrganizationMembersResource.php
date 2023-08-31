<?php

namespace App\Http\Resources\General;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Str;

class OrganizationMembersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "user_id"       =>$this->user_id,
            "name"          =>Str::title($this->name),
            "role"          =>$this->role,
            "email"         =>$this->email,
            "phone_number"  =>$this->phone_number,
            "amount_to_be_taken"=>$this->amount_to_be_taken,
            "number_of_payments"=>$this->number_of_payments,
            "status"=>$this->status,
            "received_benefit"=>$this->received_benefit,
        ];
    }
}
