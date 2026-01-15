<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = ['name', 'ranking', 'retired'];
    protected $hidden = ['created_at','updated_at'];
}
