<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['category','description'];

    protected $guarded = ['created_at','updated_at'];
    protected $hidden = ['created_at','updated_at'];
}
