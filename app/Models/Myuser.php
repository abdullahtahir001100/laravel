<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Myuser extends Model
{
    protected $fillable = ['name', 'email', 'password'];
}
