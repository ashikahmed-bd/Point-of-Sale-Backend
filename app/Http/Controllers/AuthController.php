<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
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

        $stores = $user->stores()
            ->where('stores.is_active', true)
            ->wherePivot('is_active', true)
            ->get();

        if ($stores->isEmpty()) {
            Auth::logout();

            return response()->json([
                'success' => false,
                'message' => 'No active store is assigned to your account.',
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
                'since' => $user->created_at?->format('M d, Y'),
            ],
            'stores' => $stores->map(fn($store) => [
                'id' => $store->id,
                'name' => $store->name,
                'code' => $store->code,
                'default' => (bool) $store->pivot->is_default,
            ]),
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


    public function user(Request $request)
    {
        $user = $request->user();
        return UserResource::make($user);
    }
}
