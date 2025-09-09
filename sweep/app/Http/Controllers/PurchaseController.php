<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function buy(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        if ($product->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Product not available'], 400);
        }

        if ($product->user_id === $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Cannot buy your own product'], 400);
        }

        // Placeholder for payment: In real app, integrate Stripe here
        // Assume success for now

        $product->status = 'sold';
        $product->save();

        Purchase::create([
            'buyer_id' => $request->user()->id,
            'product_id' => $productId,
            'amount' => $product->price,
        ]);

        return response()->json(['success' => true, 'message' => 'Product purchased']);
    }
}