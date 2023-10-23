<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupPayout extends Model
{
    use HasFactory;
    protected $fillable = [
        "uuid",
        "admin_id",
        "withdrawal_request_id",
        "recipient_id",
        "recipient_code",
        "organization_id",
        "transfer_code",
        "amount_payable",
        "status",
        "initialized_at",
        "finalized_at"
    ];

    protected $guarded = ["created_at","updated_at"];
}
