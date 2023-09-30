<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use App\Http\Resources\User\PaymentMethodsResource;

class UserDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            =>$this->id,
            'uuid'          =>$this->uuid,
            // "is_admin"      =>$this->is_admin === true ? true : false,
            "role"          =>$this->role,
            "status"        =>$this->status,
            'first_name'    =>Str::title($this->first_name),
            'last_name'     =>Str::title($this->last_name),
            'email'         =>$this->email,
            'phone_number'  =>$this->phone_number,
            'email_verified_at'=>$this->email_verified_at,
            'payout_methods'=>PaymentMethodResource::collection($this->payment_methods->where('status','active'))
        ];
    }
}
