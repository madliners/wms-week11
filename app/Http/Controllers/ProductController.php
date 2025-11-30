<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('user_id', Auth::id())->latest()->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|unique:products|max:255',
            'name' => 'required|max:255',
            'stock' => 'required|integer|min:0',
            'location' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        Product::create([
            'user_id' => Auth::id(),
            'sku' => $request->sku,
            'name' => $request->name,
            'stock' => $request->stock,
            'location' => $request->location,
            'description' => $request->description, // ✅ ADDED
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product added successfully!');
    }

    public function edit(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'sku' => 'required|unique:products,sku,' . $product->id . '|max:255',
            'name' => 'required|max:255',
            'stock' => 'required|integer|min:0',
            'location' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $product->update([
            'sku' => $request->sku,
            'name' => $request->name,
            'stock' => $request->stock,
            'location' => $request->location,
            'description' => $request->description, // ✅ ADDED
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
