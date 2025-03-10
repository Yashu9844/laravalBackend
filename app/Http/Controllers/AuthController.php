<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // ✅ Import Validator
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Firebase\JWT\JWT;
class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $hashedPassword = Hash::make($request->password);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $hashedPassword,
        ]);

        return response()->json(['message' => 'Successfully signed up','user'=>$user], 201);
    }

    public function signin(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Attempt login
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();

        // Issue Sanctum Token
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token  
        ], 200);
    }
}
