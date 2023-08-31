<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrganizationMember;
use App\Models\User;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        "unique_id",
        "user_id",
        "title",
        "description",
        "number_of_participants",
        "cycle_period",
        "number_of_cycles",
        "amount_per_cycle",
        "amount_per_member",
        "currency",
        "commencement_date",
        "status",
        "approval",
    ];

    protected $guarded = ["created_at","updated_at"];

    protected $casts = ["order_of_members"=>"array"];

    //* Relationships
    public function members(){
        return $this->hasMany(OrganizationMember::class,"organization_id");
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
