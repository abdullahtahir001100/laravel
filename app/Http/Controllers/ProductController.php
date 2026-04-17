<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        // If `id` is provided we update, otherwise create
        if ($request->filled('id')) {
            $product = Product::find($request->id);
            if (! $product) {
                return response()->json(['status' => false, 'message' => 'Product not found'], 404);
            }

            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'base_price' => $request->base_price,
                'category' => $request->category,
                'status' => $request->status,
            ]);

            // replace variants if provided
            if ($request->has('variants')) {
                $product->variants()->delete();
                foreach ($request->variants as $variant) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'variant_name' => $variant['name'] ?? null,
                        'variant_value' => $variant['value'] ?? null,
                        'price' => $variant['price'] ?? null,
                        'stock' => $variant['stock'] ?? null,
                    ]);
                }
            }

            // append new images if uploaded
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_main' => $index == 0
                    ]);
                }
            }

            return response()->json(['status' => true, 'message' => 'Product updated', 'product' => $product]);
        }

        // create new product
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'base_price' => $request->base_price,
            'category' => $request->category,
            'status' => $request->status,
        ]);

        // images save
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_main' => $index == 0
                ]);
            }
        }

        // variants save
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'variant_name' => $variant['name'] ?? null,
                    'variant_value' => $variant['value'] ?? null,
                    'price' => $variant['price'] ?? null,
                    'stock' => $variant['stock'] ?? null,
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Product Created Successfully',
            'product' => $product
        ]);
    }

    /**
     * Return product JSON by query id (GET /get-product?id=...)
     */
    public function show(Request $request)
    {
        $id = $request->query('id') ?? $request->id;
        if (! $id) {
            return response()->json(['status' => false, 'message' => 'Missing id'], 400);
        }

        $product = Product::with(['images', 'variants'])->find($id);
        if (! $product) {
            return response()->json(['status' => false, 'message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }
   function get(){
        $products = Product::with('images', 'variants')->get();
        return response()->json($products);
   }
}