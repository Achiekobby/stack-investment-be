<?php

namespace App\Http\Resources\General;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\User;
use  App\Http\Resources\General\ProjectPaymentResource;

class CrowdFundingProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $created_by = User::query()->where('id',$this->user_id)->first();
        return [
            "unique_id"         =>$this->unique_id,
            "created_by"        =>Str::title($created_by->first_name)." ".Str::title($created_by->last_name),
            'title'             =>$this->title,
            "category"          =>$this->category,
            "description"       =>$this->description ?? null,
            "amount_requested"  =>$this->amount_requested,
            "amount_donated"    =>$this->amount_received,
            "number_of_donors"  =>$this->number_of_donors,
            "project_status"    =>$this->project_status,
            "approval"          =>$this->approval,
            "approved_on"       =>is_null($this->approved_on) ? null : Carbon::createFromFormat('Y-m-d H:i:s',$this->approved_on)->format('Y-m-d'),
            "project_created_at"=>Carbon::createFromFormat('Y-m-d H:i:s',$this->created_at)->format('Y-m-d'),
            "donations"=>ProjectPaymentResource::collection($this->project_payments->where("payment_status","paid"))
        ];
    }
}
