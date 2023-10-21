<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Contribution;

class ContributionCycle extends Model
{
    use HasFactory;

    protected $fillable = [
        "organization_id",
        "recipient_id",
        "number_of_participants",
        "cycle_number",
        "payment_amount",
        "payment_status"
    ];

    protected $guarded = [
        "created_at",
        "updated_at"
    ];

    public function contributions(){
        return $this->hasMany(Contribution::class);
    }
}
