@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Produk</h3>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label>Nama Produk</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div>
            <label>Kategori</label>
            <select name="category_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Harga</label>
            <input type="number" name="price" class="form-control" required>
        </div>
        <div>
            <label>Stok</label>
            <input type="number" name="stock" class="form-control" required>
        </div>
        <div>
            <label>Gambar (optional)</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-success mt-2">Simpan</button>
    </form>
</div>
@endsection
