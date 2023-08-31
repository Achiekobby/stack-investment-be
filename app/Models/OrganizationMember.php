<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organization;

class OrganizationMember extends Model
{
    use HasFactory;

    protected $fillable = [
        "organization_id",
        "user_id",
        "role",
        "name",
        "email",
        "phone_number",
        "order_number",
        "amount_to_be_taken",
        "status",
        "received_benefit",
        "number_of_payments",
    ];

    protected $guarded = ["created_at","updated_at"];

    //* Relationships
    public function organization(){
        return $this->belongsTo(Organization::class);
    }
}
