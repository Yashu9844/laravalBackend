<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model {
    use HasFactory;

    protected $fillable = [ 'title', 'content', 'image', 'category', 'slug'];

    // Automatically generate slug when setting the title
    public static function boot() {
        parent::boot();
        static::creating(function ($post) {
            $post->slug = Str::slug($post->title);
        });
    }

    // Relationship with User model
    // public function user() {
    //     return $this->belongsTo(User::class);
    // }
}

