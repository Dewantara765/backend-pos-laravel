<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(){
        return CategoryResource::collection(Category::all());
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255|unique:categories,name',
            'description' => 'required|min:3|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json( $validator->errors(), 422);
        }
        $category = Category::create(
            $request->all()
        );
        return new CategoryResource($category);
    }

    public function show(Category $category){
        return new CategoryResource($category);
    }

    public function showProducts(Category $category){
        $products = $category->products;
        return ProductResource::collection($products);
    }

    public function update(Request $request, Category $category){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'description' => 'required|min:3|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $category->update($request->all());
        return new CategoryResource($category);
    }

    public function destroy(Category $category){
        $category->delete();
        return response()->json(null, 204);
    }
}
