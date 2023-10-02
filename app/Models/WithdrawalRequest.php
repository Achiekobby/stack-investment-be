<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "user_uuid",
        "project_uuid",
        "project_name",
        "approval_status",
        "payout_status",
        "reason_for_rejection",
        "amount_to_be_withdrawn"
    ];

    protected $guarded = ['created_at','updated_at'];

    public function user(){
        return $this->belongsTo(User::class);
    }


}
