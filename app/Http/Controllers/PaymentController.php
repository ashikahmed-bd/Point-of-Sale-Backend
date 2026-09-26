<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
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
        Log::create([
            'store_id' => $store->id,
            'user_id' => auth()->id(),
            'action' => 'created',
            'subject_type' => Payment::class,
            'subject_id' => $payment->id,
            'description' => 'Payment received.',
            'properties' => [
                'amount' => $payment->amount,
                'method' => $payment->method,
            ],
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
