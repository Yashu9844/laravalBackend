<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $request->validate([
            'content' => 'required|string',
        ]);

        try {

            $comment = Comment::create([
                'content' => $request->content,
            ]);

            return response()->json($comment, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPostComments($postId){
        try {
            
        $comments = Comment::where('id',$postId)->orderBy('created_at','desc')->get();

        return response()->json($comments, 200);


        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function likeComment($commentId)
    {
        try {

            $comment = Comment::find($commentId);
    
            if (!$comment) {
                return response()->json(['message' => 'Comment not found'], 404);
            }
    
           
            $likes = $comment->likes ?? [];
    
            
            $userId = 2;
    
         
            if (in_array($userId, $likes)) {
                // If liked, remove the user from the likes array
                $comment->likes = array_diff($likes, [$userId]);
                $comment->number_of_likes--;
            } else {
               
                $comment->likes = array_merge($likes, [$userId]);
                $comment->number_of_likes++;
            }
    
           
            $comment->save();
    
            return response()->json($comment, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }


    }


    public function deleteComment($commentId)
{
    try {
        // Find the comment by ID
        $comment = Comment::find($commentId);

        // Check if the comment exists
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

      
        $comment->delete();

        return response()->json(['message' => 'Comment has been deleted'], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ], 500);
    }
}

    
public function getComments(Request $request)
{
    try {
   
        $startIndex = $request->query('startIndex', 0);
        $limit = $request->query('limit', 9);
        $sortDirection = $request->query('sort', 'asc') === 'desc' ? 'desc' : 'asc';

 
        $comments = Comment::orderBy('created_at', $sortDirection)
            ->skip($startIndex)
            ->take($limit)
            ->get();

    
        $totalComments = Comment::count();

      
        $oneMonthAgo = now()->subMonth();
        $lastMonthComments = Comment::where('created_at', '>=', $oneMonthAgo)->count();

        
        return response()->json([
            'comments' => $comments,
            'totalComments' => $totalComments,
            'lastMonthComments' => $lastMonthComments,
        ], 200);
    } catch (\Exception $e) {
 
        return response()->json([
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
