<?php

namespace App\Http\Controllers;

use App\Models\Swap;
use App\Models\Product;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class SwapController extends Controller
{




    // Get all swaps involving the authenticated user
    public function index(Request $request)
    {
        $swaps = Swap::where('proposer_id', $request->user()->id)
            ->orWhere('proposee_id', $request->user()->id)
            ->with(['proposer', 'proposee', 'proposerProduct', 'proposeeProduct'])
            ->get();

        return response()->json($swaps);
    }




    public function product_list(Request $request)
    {
        // Get the currently logged-in user
        $user = $request->user();

        // If no user is logged in, return unauthorized response
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Fetch all products belonging to the user
        $products = Product::with('user')->where('user_id', $user->id)->get();

        return response()->json([
            'message' => 'Products retrieved successfully',
            'data' => $products
        ], 200);
    }





    // Propose a swap
    public function propose(Request $request, $proposeeProductId)
    {
        $validated = $request->validate([
            'proposer_product_id' => 'required|exists:products,id',
        ]);

        $proposeeProduct = Product::findOrFail($proposeeProductId);

        if ($proposeeProduct->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Product not available'], 400);
        }

        $proposerProduct = Product::findOrFail($validated['proposer_product_id']);

        if ($proposerProduct->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'You do not own this product'], 403);
        }

        if ($proposerProduct->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Your product not available'], 400);
        }

        $swap = Swap::create([
            'proposer_id' => $request->user()->id,
            'proposee_id' => $proposeeProduct->user_id,
            'proposer_product_id' => $validated['proposer_product_id'],
            'proposee_product_id' => $proposeeProductId,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Swap proposed',
            'swap' => $swap
        ]);
    }


    //notification
    public function notification(Request $request)
    {
        $user = $request->user();
        // Count swaps where the user is the proposer
        $offer = Swap::with(['proposee', 'proposerProduct', 'proposeeProduct'])
                    ->where('proposee_id', $user->id)
                    ->get();
        return response()->json($offer);
    }



    public function accept($id)
    {
        $swap = Swap::findOrFail($id);
        $swap->update(['status' => 'accepted']);
        return response()->json(['message' => 'Swap accepted']);
    }

    public function reject($id)
    {
        $swap = Swap::findOrFail($id);
        $swap->update(['status' => 'rejected']);
        return response()->json(['message' => 'Swap rejected']);
    }
}