<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function index(Request $request)
    {
        $cartItems = CartItem::with('product')->where('user_id', $request->user()->id)->get();
        return response()->json($cartItems);
    }

    public function add(Request $request, $productId)
    {
        $user = $request->user();

        if (CartItem::where('user_id', $user->id)->where('product_id', $productId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Already in cart'], 400);
        }

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);

        return response()->json(['success' => true, 'message' => 'Added to cart']);
    }

    public function remove(Request $request, $productId)
    {
        $user = $request->user();

        $cartItem = CartItem::where('user_id', $user->id)->where('product_id', $productId)->first();

        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'Not in cart'], 404);
        }

        $cartItem->delete();

        return response()->json(['success' => true, 'message' => 'Removed from cart']);
    }
}