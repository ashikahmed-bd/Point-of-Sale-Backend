<?php

namespace App\Http\Middleware;

use App\Models\Store;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $storeId = $request->header('X-Store-ID');

        if (!$storeId) {
            return response()->json([
                'message' => 'Store is required.',
            ], 422);
        }

        $store = $request->user()
            ->stores()
            ->where('stores.id', $storeId)
            ->where('stores.is_active', true)
            ->first();

        if (!$store) {
            return response()->json([
                'message' => 'You do not have access to this store.',
            ], 403);
        }

        app()->instance(Store::class, $store);

        return $next($request);
    }
}
