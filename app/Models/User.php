<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'username',
        'email',
        'password',
        'profile_picture',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $attributes = [
        'profile_picture' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQb3CGv17TYybA-cdAD1r6gbxJjX4YuXFJy-V1D29jPKw&s',
        'is_admin' => false,
    ];
}
