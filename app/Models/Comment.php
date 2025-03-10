<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Table name (optional if not the plural of model name)
    protected $table = 'comments';

    // The attributes that are mass assignable
    protected $fillable = [
        'content',
        'likes',
        'number_of_likes',
    ];

    // Casting attributes to specific types
    protected $casts = [
        'likes' => 'array', // To store likes as an array
    ];
}
