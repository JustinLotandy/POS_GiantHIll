<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #333; padding: 6px 4px; text-align: left; }
        th { background: #eee; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h2>{{ $title }}</h2>
    <div>Periode: <b>{{ $periode }}</b></div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Transaksi</th>
                <th>User</th>
                <th>Metode</th>
                <th>Total</th>
                <th>Dibayar</th>
                <th>Kembali</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $no => $trx)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $trx->id_Transaction }}</td>
                    <td>{{ $trx->user->name ?? '-' }}</td>
                    <td>{{ $trx->paymentMethod->name_payment ?? '-' }}</td>
                    <td class="right">Rp {{ number_format($trx->total,0,',','.') }}</td>
                    <td class="right">Rp {{ number_format($trx->paid,0,',','.') }}</td>
                    <td class="right">Rp {{ number_format($trx->change,0,',','.') }}</td>
                    <td>{{ $trx->created_at }}</td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center">Tidak ada data transaksi</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
