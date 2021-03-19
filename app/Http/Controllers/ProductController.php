<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Throwable;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return response(["products" => ProductResource::collection($products), "message" => "Products received successfully!"], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // Validate the data
        $validator = Validator::make($data, [
            "name" => "required|max:255",
            "category_id"=> "required|exists:categories,id",
            "comment" => "max:500"
        ]);

        if ($validator->fails()){
            return response(["error" => $validator->errors(), "Validation Error!"]);
        }

        $product = Product::create($data);

        return response(["product" => new ProductResource($product),"message" => "Product created successfully!"], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // In case of error -> 404 Not found is displayed

        return response(["product" => new ProductResource($product), "message" => "Product retrieved successfully!"], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->all();

        // Validate the data
        $validator = Validator::make($data, [
            "name" => "required|max:255",
            "category_id"=> "exists:categories,id",
            "comment" => "max:500"
        ]);

        if ($validator->fails()){
            return response(["error" => $validator->errors(), "Validation Error!"]);
        }

        $product->update($data);

        return response(["product" => new ProductResource($product), "message" => "Product updated successfully!"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // In case of error -> 404 Not found is displayed

        $product->delete();

        return response(["message" => "Product deleted!"], 204);
    }
}
