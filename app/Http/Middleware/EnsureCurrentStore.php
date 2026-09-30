<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCurrentStore
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $storeId = $request->header('X-Store-ID');

        if (!$storeId) {
            return response()->json([
                'message' => 'Store is required.',
            ], 400);
        }

        $store = $user->stores()
            ->where('stores.id', $storeId)
            ->where('stores.is_active', true)
            ->wherePivot('is_active', true)
            ->first();

        if (!$store) {
            return response()->json([
                'message' => 'You do not have access to this store.',
            ], 403);
        }

        app()->instance('currentStore', $store);

        $request->attributes->set('current_store', $store);

        return $next($request);
    }
}
