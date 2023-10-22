<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class PayoutMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "user_id",
        "method",
        "account_name",
        "momo_number",
        "momo_provider",
        "recipient_code",
        "currency",
        "status"
    ];

    protected $hidden = ["recipient_code"];

    protected $guarded= ["created_at", "updated_at", "deleted_at"];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
