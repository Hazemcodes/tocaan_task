<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserRescource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        // Upload profile picture if provided
        if ($request->hasFile('image')) {
            $user->addMedia($request->file('image'))->toMediaCollection('profile_pictures');
        }
        // Return user with UserRescource
        return response()->json(
            [
                'code' => 201,
                'data' => new UserRescource($user),
                'message' => 'User created successfully',
            ]
        );
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);

            return response()->json(compact('token'));
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    // public function protectedEndpoint(Request $request)
    // {
    //     try {
    //         $user = JWTAuth::parseToken()->authenticate();
    //     } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
    //         return response()->json(['error' => 'Token expired'], 401);
    //     } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
    //         return response()->json(['error' => 'Invalid token'], 401);
    //     } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
    //         return response()->json(['error' => 'Token is missing'], 401);
    //     }

    //     // Access user data or perform actions requiring authentication
    //     return response()->json(['message' => 'Success! You are authorized.']);
    // }
}
