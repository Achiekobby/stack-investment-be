<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//* Models
use App\Models\User;

class Subaccount extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','sub_account_code','business_name','settlement_bank','percentage_charge','subaccount_status'];

    protected $guarded = ['updated_at','created_at'];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
