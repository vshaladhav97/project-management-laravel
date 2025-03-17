<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('products.index', ["products" => $products]);
    }

    public function store(Request $request)
    {
        $product = Product::create($request->all());
        return response()->json(['success' => true, 'product' => $product]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->only(['product_name', 'quantity_in_stock', 'price_per_item']));

        return response()->json(['success' => true]);

    }

    public function fetchData()
    {
        $products = Product::all()->sortByDesc('created_at');
        return response()->json($products);
    }
}
