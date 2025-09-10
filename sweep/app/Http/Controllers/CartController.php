<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{


    // Get all items in the current user's cart
    public function index(Request $request)
    {

        // Fetch all cart items for the logged-in user, along with product details (via relation)

        $cartItems = CartItem::with('product')->where('user_id', $request->user()->id)->get();
        return response()->json($cartItems);
    }



    // Add a product to the cart
    public function add(Request $request, $productId)
    {
        $user = $request->user();


        // Prevent duplicate items: check if this product is already in the cart
        if (CartItem::where('user_id', $user->id)->where('product_id', $productId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Already in cart'], 400);
        }


        // If not in the cart, create a new cart item record
        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);

        return response()->json(['success' => true, 'message' => 'Added to cart']);
    }


    // Remove a product from the cart
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