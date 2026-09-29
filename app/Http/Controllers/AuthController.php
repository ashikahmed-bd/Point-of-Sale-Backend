<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to login with the provided credentials. Please check your email and password.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $request->user();

        if ($user->disabled) {
            Auth::logout();

            return response()->json([
                'success' => false,
                'message' => 'Your account has been disabled. Please contact support.',
            ], Response::HTTP_FORBIDDEN);
        }

        return response()->json([
            'message' => 'You are logged in successfully!',
            'type' => 'Bearer',
            'token' => $user->createToken('token', [$user->role], now()->addWeek())->plainTextToken,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'photo_url' => $user->photo_url,
                'role' => $user->role,
                'since' => $user->created_at?->format('M d, Y'),
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function logout(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function forgot(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function reset(Request $request)
    {
        //
    }
}
