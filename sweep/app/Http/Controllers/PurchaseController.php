<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of purchases.
     */
    public function index()
    {
        $purchases = Purchase::with(['buyer', 'product'])->latest()->get();
        return response()->json($purchases);
    }

    /**
     * Create a new purchase (custom buy method).
     */
    public function buy(Request $request, $productId)
    {
        $validated = $request->validate([
            'buyer_id' => 'required|exists:users,id',

            // Card info
            'card_number' => 'nullable|string',
            'expiry_date' => 'nullable|string',
            'cvv' => 'nullable|string',
            'cardholder_name' => 'nullable|string',

            // Mobile banking
            'mobile_banking' => 'nullable|in:bkash,rocket,nagad,upay',
            'payment_mobile' => 'nullable|string',

            // Billing info
            'email' => 'required|email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'address' => 'required|string',
            'mobile' => 'required|string',
            'city' => 'required|string',
            'division' => 'required|in:Dhaka,Chattogram,Rajshahi,Khulna,Sylhet,Barishal,Rangpur,Mymensingh',
            'post_code' => 'required|string',

            // Prices
            'price' => 'required|integer',
            'delivery' => 'required|integer',
            'service' => 'required|integer',
        ]);

        $validated['product_id'] = $productId;

        $purchase = Purchase::create($validated);

        return response()->json([
            'message' => 'Purchase created successfully',
            'data' => $purchase
        ], 201);
    }

    /**
     * Display a specific purchase.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['buyer', 'product']);
        return response()->json($purchase);
    }

    /**
     * Update a purchase.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'card_number' => 'nullable|string',
            'expiry_date' => 'nullable|string',
            'cvv' => 'nullable|string',
            'cardholder_name' => 'nullable|string',

            'mobile_banking' => 'nullable|in:bkash,rocket,nagad,upay',
            'payment_mobile' => 'nullable|string',

            'email' => 'sometimes|email',
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'address' => 'sometimes|string',
            'mobile' => 'sometimes|string',
            'city' => 'sometimes|string',
            'division' => 'sometimes|in:Dhaka,Chattogram,Rajshahi,Khulna,Sylhet,Barishal,Rangpur,Mymensingh',
            'post_code' => 'sometimes|string',

            'price' => 'sometimes|integer',
            'delivery' => 'sometimes|integer',
            'service' => 'sometimes|integer',
        ]);

        $purchase->update($validated);

        return response()->json([
            'message' => 'Purchase updated successfully',
            'data' => $purchase
        ]);
    }

    /**
     * Remove a purchase.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return response()->json([
            'message' => 'Purchase deleted successfully'
        ]);
    }
}
