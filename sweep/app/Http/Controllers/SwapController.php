<?php

namespace App\Http\Controllers;

use App\Models\Swap;
use App\Models\Product;
use Illuminate\Http\Request;

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



    // Accept a swap
    public function accept(Request $request, $id)
    {
        $swap = Swap::findOrFail($id);

        if ($swap->proposee_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($swap->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Swap not pending'], 400);
        }

        $swap->status = 'accepted';
        $swap->save();

        // Mark products as swapped
        $proposerProduct = Product::findOrFail($swap->proposer_product_id);
        $proposerProduct->status = 'swapped';
        $proposerProduct->save();

        $proposeeProduct = Product::findOrFail($swap->proposee_product_id);
        $proposeeProduct->status = 'swapped';
        $proposeeProduct->save();

        return response()->json(['success' => true, 'message' => 'Swap accepted']);
    }





    // Reject a swap
    public function reject(Request $request, $id)
    {
        $swap = Swap::findOrFail($id);

        if ($swap->proposee_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($swap->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Swap not pending'], 400);
        }

        $swap->status = 'rejected';
        $swap->save();

        return response()->json(['success' => true, 'message' => 'Swap rejected']);
    }
}