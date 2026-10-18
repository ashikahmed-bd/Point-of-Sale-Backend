<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\JsonResponse;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Reset all tables and seed all data.
     */
    public function reset(): JsonResponse
    {
        try {
            Artisan::call('migrate:fresh', [
                '--seed' => true,
                '--force' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'All tables migrated and seeded successfully.',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reset and seed database.',
            ], 500);
        }
    }

    /**
     * Seed all tables.
     */
    public function seed(): JsonResponse
    {
        try {
            Artisan::call('db:seed', [
                '--force' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'All seeders executed successfully.',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Failed to seed database.',
            ], 500);
        }
    }
}
