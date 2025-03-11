<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Http\Response;
use App\Models\Post;
use Illuminate\Support\Str;
use Carbon\Carbon;
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

    public function getPosts(Request $request)
    {
        try {
            $startIndex = $request->query('startIndex', 0);
            $limit = $request->query('limit', 9);
            $sortDirection = $request->query('order') === 'asc' ? 'asc' : 'desc';

            // Build query
            $query = Post::query();

            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            if ($request->has('slug')) {
                $query->where('slug', $request->slug);
            }

            if ($request->has('postId')) {
                $query->where('id', $request->postId);
            }

            if ($request->has('searchTerm')) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'LIKE', '%' . $request->searchTerm . '%')
                      ->orWhere('content', 'LIKE', '%' . $request->searchTerm . '%');
                });
            }

       
            $posts = $query->orderBy('updated_at', $sortDirection)
                           ->skip($startIndex)
                           ->take($limit)
                           ->get();

         
            $totalPosts = Post::count();

            // Count posts from last month
            $oneMonthAgo = Carbon::now()->subMonth();
            $lastMonthPosts = Post::where('created_at', '>=', $oneMonthAgo)->count();

            return response()->json([
                'posts' => $posts,
                'totalPosts' => $totalPosts,
                'lastMonthPosts' => $lastMonthPosts
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

 public function deletePost($postId){

    try {
         $post = Post::find($postId);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }


        $post->delete();

        return response()->json(['message' => 'Post has been deleted'], 200);


    } catch (\Throwable $th) {
        return response()->json([
           'message' => 'Something went wrong',
            'error' => $th->getMessage()
        ], 500);
    }
 }


 public function updatePost(Request $request, $postId)
 {
     try {
         $post = Post::find($postId);

         if (!$post) {
             return response()->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
         }

         // Validate request
         $request->validate([
             'title' => 'sometimes|string|unique:posts,title,' . $postId,
             'content' => 'sometimes|string',
             'category' => 'sometimes|string',
             'image' => 'sometimes|string',
         ]);

         // Update fields if provided
         $post->update($request->only(['title', 'content', 'category', 'image']));

         return response()->json($post, Response::HTTP_OK);
     } catch (\Exception $e) {
         return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
     }
 }
}