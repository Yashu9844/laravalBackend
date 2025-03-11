<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller 
{
    public function updateUser(Request $request, $userId)
    {
        
        $user = User::find($userId);
    
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        $validatedData = $request->validate([
            'password' => 'nullable|string|min:6',
            'username' => [
                'nullable',
                'string',
                'min:7',
                'max:20',
                'regex:/^[a-z0-9]+$/',
            ],
            'email' => 'nullable|email',
            'profilePicture' => 'nullable|string'
        ]);
    
        // Hash password if provided
        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        }
    
        // Update user
        $user->update($validatedData);
    
        return response()->json($user->only(['id', 'username', 'email', 'profilePicture']), 200);
    }

    public function getUser(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user->only(['id', 'username', 'email', 'profilePicture']), 200);
    }


    public function deleteUser($id){
        // $user = Auth::user();

        // if(!$user || !$user->is_admin && $user->id != $id){
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }
        $id = (int) $id;
$userToDelete = User::find($id);

if(!$userToDelete){
    return response()->json(['message'=>'user not found'], 404);
}
$userToDelete->delete();

return response()->json(['message'=>'User has deleted'],200);

    }


    public function signout(Request $request) {
        $user = Auth::user();
    
        if ($user) {
            $request->user()->tokens()->delete(); // Revoke all tokens
            return response()->json(['message' => 'User has been signed out'], 200);
        }
    
        return response()->json(['message' => 'Unauthorized'], 401);
    }


    
    public function getUsers(Request $request) {
     
        $startIndex = intval($request->query('startIndex', 0));
        $limit = intval($request->query('limit', 9));
        $sortDirection = $request->query('sort', 'desc') === 'asc' ? 'asc' : 'desc';
    
        $users = User::orderBy('created_at', $sortDirection)
            ->skip($startIndex)
            ->take($limit)
            ->get();
    

        $usersWithoutPassword = $users->map(function ($user) {
            return collect($user)->except(['password']);
        });

        $totalUsers = User::count();
    

        $oneMonthAgo = now()->subMonth();
        $lastMonthUsers = User::where('created_at', '>=', $oneMonthAgo)->count();
    
        return response()->json([
            'users' => $usersWithoutPassword,
            'totalUsers' => $totalUsers,
            'lastMonthUsers' => $lastMonthUsers
        ], 200);
    }
}


