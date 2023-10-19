<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organization;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        "uuid",
        "user_id",
        "organization_id",
        "user_name",
        "email",
        "user_phone",
        "link",
        "status",
    ];

    protected $guarded =["created_at","updated_at"];

    public function organization(){
        return $this->belongsTo(Organization::class);
    }

}
