<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

use App\Models\Project;
use App\Models\OrganizationMember;
use App\Models\PaymentMethod;
use App\Models\WithdrawalRequest;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'uuid',
        'email',
        'phone_number',
        'password',
        'email_verified_at',
        'email_verification_code',
        'password_reset_code',
        'profile_picture',
        "role",
        "status"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //* Relationships
    public function projects(){
        return $this->hasMany(Project::class);
    }

    public function organizationMembers(){
        return $this->hasMany(OrganizationMember::class,"user_id");
    }

    public function organizations(){
        return $this->hasManyThrough(Organization::class,OrganizationMember::class,"user_id","id","id","organization_id");
    }

    public function payment_methods(){
        return $this->hasMany(PaymentMethod::class);
    }

    public function withdrawal_requests(){
        return $this->hasMany(WithdrawalRequest::class);
    }
}
