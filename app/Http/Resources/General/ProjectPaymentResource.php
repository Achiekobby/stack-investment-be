<?php

namespace App\Http\Resources\General;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ProjectPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "first_name"=>$this->first_name,
            "last_name" =>$this->last_name,
            "email"=>$this->email,
            "amount"=>$this->amount_donated,
            "medium_of_payment"=>$this->medium_of_payment,
            "paid_at"=>Carbon::createFromFormat('Y-m-d H:i:s',$this->paid_at)->format("Y-m-d"),
        ];
    }
}
