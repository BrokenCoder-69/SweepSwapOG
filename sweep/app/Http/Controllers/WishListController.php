<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{

    // Get the wishlist for the authenticated user
    public function index(Request $request)
    {
        $wishlists = Wishlist::with('product')->where('user_id', $request->user()->id)->get();
        return response()->json($wishlists);
    }






    // Add a product to the wishlist
    public function add(Request $request, $productId)
    {
        $user = $request->user();

        if (Wishlist::where('user_id', $user->id)->where('product_id', $productId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Already in wishlist'], 400);
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);

        return response()->json(['success' => true, 'message' => 'Added to wishlist']);
    }








    // Remove a product from the wishlist
    public function remove(Request $request, $productId)
    {
        $user = $request->user();

        $wishlist = Wishlist::where('user_id', $user->id)->where('product_id', $productId)->first();

        if (!$wishlist) {
            return response()->json(['success' => false, 'message' => 'Not in wishlist'], 404);
        }

        $wishlist->delete();

        return response()->json(['success' => true, 'message' => 'Removed from wishlist']);
    }
}