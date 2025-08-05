@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Produk</h3>
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div>
            <label>Nama Produk</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>
        <div>
            <label>Kategori</label>
            <select name="category_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Harga</label>
            <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
        </div>
        <div>
            <label>Stok</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
        </div>
        <div>
            <label>Gambar Saat Ini</label><br>
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" width="70"><br>
            @else
                <span>Tidak ada gambar</span><br>
            @endif
            <label>Ubah Gambar (optional)</label>
            <input type="file" name="image" class="form-control mt-1">
        </div>
        <button type="submit" class="btn btn-success mt-2">Update</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary mt-2">Batal</a>
    </form>
</div>
@endsection
