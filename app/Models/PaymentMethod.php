<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "account_type",
        "account_name",
        "account_number",
        "momo_number",
        "currency",
        "status",
        "bank_name",
        "momo_network",
    ];

    public function setAccountNumberAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['account_number'] = encrypt($value); // Mask the account number before storing
        } else {
            $this->attributes['account_number'] = null;
        }
    }

    public function getAccountNumberAttribute($value)
    {
        if ($value !== null) {
            return decrypt($value);
        }

        return null;
    }



    public function user(){
        return $this->belongsTo(User::class);
    }
}
