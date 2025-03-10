<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;

class PostController extends Controller {
    public function create(Request $request) {
        // Validate request data
        $request->validate([
            
            'title' => 'required|string|unique:posts,title',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'category' => 'nullable|string',
        ]);
    
        // Generate slug from title
        $slug = Str::slug($request->title);
    
        try {
            // Create new post (without user authentication)
            $post = Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $request->image ?? 'https://www.hostinger.com/tutorials/wp-content/uploads/sites/2/2021/09/how-to-write-a-blog-post.png',
                'category' => $request->category ?? 'uncategorized',
                'slug' => $slug,
            ]);
    
            return response()->json($post, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
        }
    }
}