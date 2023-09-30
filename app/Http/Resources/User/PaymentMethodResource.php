<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "account_name"  =>$this->account_name,
            "account_type"  =>$this->account_type,
            "account_number"=>$this->account_number,
            "bank_name"     =>$this->bank_name,
            "momo_number"   =>$this->momo_number,
            "momo_network"  =>$this->momo_network,
            "status"        =>$this->status,
        ];
    }
}
