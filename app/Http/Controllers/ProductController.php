<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function edit($id)
{
    $product = Product::findOrFail($id);
    $categories = \App\Models\Category::all();
    return view('products.edit', compact('product', 'categories'));
}
   public function store(Request $request)
{
    $request->validate([
        'id_Produk' => 'nullable|string|max:36|unique:products,id_Produk',
        'name' => 'required',
        'barcode' => 'nullable|string|max:64|unique:products,barcode',
        'category_id' => 'required|exists:categories,id_kategori',
        'harga_sebelum' => 'required|numeric',
        'harga_sesudah' => 'required|numeric',
        'stock' => 'required|integer',
        'image' => 'nullable|image|max:2048',
    ]);

    $data = $request->except(['image']);
    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    Product::create($data);
    return redirect()->route('products.index')->with('success', 'Produk berhasil ditambah!');
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'barcode' => 'nullable|string|max:64|unique:products,barcode,' . $id . ',id_Produk',
        'category_id' => 'required|exists:categories,id_kategori',
        'harga_sebelum' => 'required|numeric',
        'harga_sesudah' => 'required|numeric',
        'stock' => 'required|integer',
        'image' => 'nullable|image|max:2048',
    ]);

    $product = Product::findOrFail($id);
    $data = $request->except(['image']);

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    $product->update($data);
    return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate!');
}


    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            \Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
