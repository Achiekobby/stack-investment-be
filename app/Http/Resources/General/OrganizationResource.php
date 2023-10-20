<?php

namespace App\Http\Resources\General;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\OrganizationMember;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Http\Resources\General\OrganizationMembersResource;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $created_by = User::query()->where("id", $this->user_id)->first();
        $number_of_members = OrganizationMember::query()->where("organization_id",$this->organization_id)->get()->count();
        return [
            "unique_id"             =>$this->unique_id,
            'title'                 =>$this->title,
            'description'           =>$this->description,
            'created_at'            =>Carbon::createFromFormat('Y-m-d H:i:s',$this->created_at)->format('Y-m-d'),
            "created_by"            =>Str::title($created_by->first_name)." ".Str::title($created_by->last_name),
            "maturity"              =>$this->cycle_period,
            "number_of_cycles"      =>$this->number_of_cycles,
            "number_of_members"     =>$this->number_of_participants,
            "max_number_of_members" =>$this->max_number_of_members,
            "total_amount_per_cycle"=>number_format((float)$this->amount_per_cycle,2,'.',''),
            "commencement_date"     =>$this->commencement_date,
            "status"                =>$this->status,
            "members"               =>OrganizationMembersResource::collection($this->members),
        ];
    }
}
