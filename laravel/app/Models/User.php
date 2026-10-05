<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens; 

    protected $fillable = ['username', 'email', 'password', 'role', 'bio'];
    protected $hidden = ['password'];
}