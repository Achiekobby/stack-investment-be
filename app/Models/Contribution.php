<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    use HasFactory;

    public $fillable = [
        "contribution_cycle_id",
        "user_id",
        "amount",
        "payment_status",
        "payment_date"
    ];

    public function contribution_cycle(){
        return $this->belongsTo(ContributionCycle::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
