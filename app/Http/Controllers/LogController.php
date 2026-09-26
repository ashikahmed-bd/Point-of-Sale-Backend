<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $logs = Log::with([
                'user:id,name',
                'subject',
            ])
            ->when(
                $request->filled('action'),
                fn ($query) => $query->where('action', $request->action)
            )
            ->when(
                $request->filled('user_id'),
                fn ($query) => $query->where('user_id', $request->user_id)
            )
            ->when(
                $request->filled('subject_type'),
                fn ($query) => $query->where(
                    'subject_type',
                    $request->subject_type
                )
            )
            ->latest()
            ->paginate($request->integer('limit', 20));
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
    public function show(Log $log)
    {
        $log->load([
            'user:id,name',
            'subject',
        ]);

        return response()->json([
            'log' => $log,
        ]);
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
}
