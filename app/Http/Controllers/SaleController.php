<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
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

        $sale = Sale::create([

        ]);

        $sale->logs()->create([
            'store_id' => $sale->store_id,
            'user_id' => $request->user()?->id,
            'action' => 'created',
            'subject_type' => Sale::class,
            'subject_id' => $sale->id,
            'description' => 'Sale created.',
        ]);
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
}
