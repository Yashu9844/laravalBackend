<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
           
            $table->string('title')->unique();
            $table->text('content');
            $table->string('image')->default('https://www.hostinger.com/tutorials/wp-content/uploads/sites/2/2021/09/how-to-write-a-blog-post.png');
            $table->string('category')->default('uncategorized');
            $table->string('slug')->unique();
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    public function down() {
        Schema::dropIfExists('posts');
    }
};
