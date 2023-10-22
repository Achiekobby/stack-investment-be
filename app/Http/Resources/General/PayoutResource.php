<?php

namespace App\Http\Resources\General;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PayoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"            =>$this->id,
            "account_name"  =>Str::title($this->account_name),
            "payment_medium"=>$this->method,
            "momo_number"   =>$this->momo_number,
            "momo_provider" =>$this->momo_provider,
            "status"        =>"active"
        ];
    }
}
