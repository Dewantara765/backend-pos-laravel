<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(){
        $products = Product::with('category')->get();
        return ProductResource::collection($products);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255|unique:products,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id_category',
            'stock' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json( $validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('products', $image->hashName(), 'public');


        $product = Product::create([
            'name' => $request->name,
            'barcode' => $request->barcode,
            'image' => $image->hashName(),
            'price' => $request->price,
            'category_id' => $request->category_id,
            'stock' => $request->stock ?? 0,
        ]);
        return new ProductResource($product->load('category'));
    }

    public function show(Product $product){
        return new ProductResource($product->load('category'));
    }

    public function update(Request $request, Product $product){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id_category',
            'stock' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->hasFile('image')) {

        // Hapus gambar lama jika ada
        if ($product->image && Storage::disk('public')->exists("products/{$product->image}")) {
            Storage::disk('public')->delete("products/{$product->image}");
        }

        $image = $request->file('image');
        $image->storeAs('products', $image->hashName(), 'public');
        $product->image = $image->hashName();
    }

    // Update data product
    $product->update([
        'name' => $request->name,
        'price' => $request->price,
        'category_id' => $request->category_id,
        'stock' => $request->stock ?? $product->stock,
        'image' => $product->image,
    ]);

        return new ProductResource($product->load('category'));
    }

    public function destroy(Product $product){
        // Hapus gambar produk jika ada
        if ($product->image && Storage::disk('public')->exists("products/{$product->image}")) {
            Storage::disk('public')->delete("products/{$product->image}");
        }

        $product->delete();
        return response()->json(null, 204);
    }
}
