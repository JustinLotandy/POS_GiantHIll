<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function create()
{
    return view('categories.create');
}

public function store(Request $request)
{
    $request->validate([
        'id_kategori' => 'nullable|string|max:36|unique:categories,id_kategori',
        'nama_kategori' => 'required|string|max:255',
    ]);

    Category::create([
        'id_kategori' => $request->id_kategori ,
        'nama_kategori' => $request->nama_kategori,
    ]);

    return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambah!');
}

public function edit($id)
{
    $category = Category::findOrFail($id);
    return view('categories.edit', compact('category'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'id_kategori' => 'nullable|string|max:36|unique:categories,id_kategori,' . $id . ',id_kategori',
        'nama_kategori' => 'required|string|max:255',
    ]);

    $category = Category::findOrFail($id);
    $category->update([
        'id_kategori' => $request->id_kategori ?? $category->id_kategori,
        'nama_kategori' => $request->nama_kategori,
    ]);

    return redirect()->route('categories.index')->with('success', 'Kategori berhasil diupdate!');
}

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
