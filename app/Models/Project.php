<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ProjectPayment;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        "unique_id",
        "user_id",
        "title",
        "description",
        "amount_requested",
        "amount_received",
        "number_od_donors",
        "project_status",
        "approval",
        "approved_on",
    ];

    protected $guarded = ["created_at","updated_at"];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function project_payments(){
        return $this->hasMany(ProjectPayment::class);
    }
}
