<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;

class ProjectPayment extends Model
{
    protected $fillable = [
        "unique_id",
        "project_id",
        "user_id",
        "amount_donated",
        "donation_id",
        "donation_reference",
        "medium_of_payment",
        "payment_status",
        "paid_at",
    ];

    protected $guarded = ["created_at","updated_at"];

    use HasFactory;

    //* Relationships
    public function project(){
        return $this->belongsTo(Project::class);
    }
}
