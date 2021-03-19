<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return response(["categories" => CategoryResource::collection($categories), "message" => "Retrieved successfully"], 200);
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
            "comment" => "max:500"
        ]);

        if ($validator->fails()) {
            return response(["error" => $validator->errors(), "Validation error!"]);
        }

        $category = Category::create($data);

        return response(["category" => new CategoryResource($category), "message" => "Category created successfully!"], 201); // 201 => object created

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        // In case of error -> 404 Not found is displayed

        return response(["category" => new CategoryResource($category), "message" => "Category retrieved successfully!"], 200); // 200 => "OK"
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->all();

        // Validate the data
        $validator = Validator::make($data, [
            "name" => "required|max:255",
            "comment" => "max:500"
        ]);

        if ($validator->fails()) {
            return response(["error" => $validator->errors(), "Validation error!"]);
        }

        $category->update($data);

        return response(["category" => new CategoryResource($category), "message" => "Category updated successfully"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // In case of error -> 404 Not found is displayed

        $category->delete();

        return response(["message" => "Category deleted!"], 204);
    }


    public function getProducts(Request $request)
    {
        // In case of error -> "No products found"

        $id = $request->get("id");
        $products = Product::where("category_id", $id)->get();
        $message = count($products) > 0 ? "Retrieved successfully" : "No products found!";

        return response(["products" => ProductResource::collection($products), "message" => $message]);
    }
}
