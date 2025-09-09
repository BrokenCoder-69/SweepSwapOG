<?php

namespace App\Http\Controllers;

use App\Models\Product;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        // $query = Product::with('user');
        $query = Product::query();
        // dd($query);

    // Eager load the 'user' relationship for admins (role = 1)
        if ($request->user() && $request->user()->role === 1) {
            $query->with('user');
        }

        // Only show active products for non-admins (role != 1) or unauthenticated users
        if (!$request->user() || $request->user()->role !== 1) {
            $query->where('status', 'active');
        }

        return response()->json($query->get());
    }







    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'is_used' => 'required|boolean',
            'usage_duration' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            // 'images' => 'nullable|images|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        $imagePaths = null;

        if ($request->hasFile('images')) {
            

            $images = $request->file('images');
            $path = $images->store('products', 'public');
            $imagePaths = Storage::url($path);
            
        }


        $product = Product::create([
            'user_id'        => $request->user()->id,
            'name'           => $validated['name'],
            'description'    => $validated['description'],
            'category'       => $validated['category'],
            'is_used'        => $validated['is_used'],
            'usage_duration' => $validated['usage_duration'] ?? null,
            'price'          => $validated['price'],
            'images'         => $imagePaths,
            'status'         => 'active',
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }





    public function show($id)
    {
        return response()->json(Product::with('user')->findOrFail($id));
    }





    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id !== $request->user()->id && $request->user()->role !== 1) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'category' => 'sometimes|required|string|max:255',
            'is_used' => 'sometimes|required|boolean',
            'usage_duration' => 'nullable|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'images' => 'images|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle new images if uploaded
        
        if ($request->hasFile('images')) {
            // Delete old images
                $relativePath = str_replace('/storage/', '', $product->images);
                Storage::disk('public')->delete($relativePath);
            

            $imagePaths = [];
            $images = $request->file('images');
            $path = $images->store('products', 'public');
            $imagePaths[] = Storage::url($path);
            
            $validated['images'] = $imagePaths;
        }

        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
    }






     // Delete a product
    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id !== $request->user()->id && $request->user()->role !== 1) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // // Delete images
        // foreach ($product->images ?? [] as $image) {
        //     $relativePath = str_replace('/storage/', '', $image);
        //     Storage::disk('public')->delete($relativePath);
        // }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
}
