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
   function get(){
        $products = Product::with('images', 'variants')->get();
        return response()->json($products);
   }
}