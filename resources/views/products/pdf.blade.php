<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Daftar Produk Minimarket Giant Hill</h2>
    <table>
        <thead>
            <tr>
                <th>ID Produk</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Harga Sebelum</th>
                <th>Harga Sesudah</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
                <tr>
                    <td>{{ $p->id_Produk }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->category->nama_kategori ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($p->harga_sebelum,0,',','.') }}</td>
                    <td class="text-right">Rp {{ number_format($p->harga_sesudah,0,',','.') }}</td>
                    <td>{{ $p->stock }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 20px; text-align:right; font-size:11px;">
        Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }}
    </p>
</body>
</html>
