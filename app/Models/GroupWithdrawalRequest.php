<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupWithdrawalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "organization_id",
        "amount_to_withdraw",
        "cycle_number",
        "group_admin_name",
        "payment_method_id"
    ];

    public function organization(){
        return $this->hasMany(Organization::class);
    }
}
