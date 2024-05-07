<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('pages.products.index', compact('products'));
    }

    public function create()
    {
        try {
            $categories = Category::all();
            $product = null;
            return view('pages.products.form', compact('categories', 'product'));
        } catch (Exception $e) {
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'description'   => 'nullable',
            'amount'        => 'nullable|min:0',
            'quantity'      => 'nullable|min:0',
            'image_paths.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id'   => 'required|exists:categories,id',
        ]);
        try {
            $product = Product::create([
                'name'        => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'amount'      => $request->amount,
                'quantity'    => $request->quantity,
            ]);

            if ($request->hasFile('image_paths')) {
                $images = [];
                foreach ($request->file('image_paths') as $image) {
                    $path     = $image->store('products', 'public');
                    $images[] = $path;
                }
                $product->update(['image_path' => $images]);
            }

            return redirect()->route('products.index')->with('success', 'Product created successfully');
        } catch (Exception $e) {
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('pages.products.form', compact('categories', 'product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'          => 'required',
            'description'   => 'nullable',
            'amount'        => 'nullable|min:0',
            'quantity'      => 'nullable|min:0',
            'image_paths.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id'   => 'required|exists:categories,id',
        ]);
        try {
            $product->update([
                'name'        => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'amount'      => $request->amount,
                'quantity'    => $request->quantity,
            ]);

            if ($request->hasFile('image_paths')) {
                $images = [];
                foreach ($request->file('image_paths') as $image) {
                    $path     = $image->store('products', 'public');
                    $images[] = $path;
                }
                $product->update(['image_paths' => $images]);
            }

            return redirect()->route('products.index')->with('success', 'Product updated successfully');
        } catch (Exception $e) {
        }
    }

    public function destroy(Product $product)
    {
        try {
            if ($product->image_paths) {
                foreach ($product->image_paths as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Product deleted successfully');
        } catch (Exception $e) {
        }
    }
}
