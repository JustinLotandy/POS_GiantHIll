{{-- Ringkasan --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-3 p-4">
  <div class="rounded-xl border bg-white p-4">
    <div class="text-xs text-gray-500">Omzet</div>
    <div class="text-2xl font-bold text-blue-600">
      Rp {{ number_format($summary['omzet'] ?? 0, 0, ',', '.') }}
    </div>
  </div>
  <div class="rounded-xl border bg-white p-4">
    <div class="text-xs text-gray-500">Profit</div>
    <div class="text-2xl font-bold text-green-600">
      Rp {{ number_format($summary['profit'] ?? 0, 0, ',', '.') }}
    </div>
  </div>
  <div class="rounded-xl border bg-white p-4">
    <div class="text-xs text-gray-500">Jumlah Transaksi</div>
    <div class="text-2xl font-bold">
      {{ number_format($summary['count'] ?? 0) }}
    </div>
  </div>
</div>

<table class="min-w-full text-sm">
  <thead class="bg-gray-50">
    <tr>
      <th class="px-3 py-2 text-left">ID</th>
      <th class="px-3 py-2 text-left">User</th>
      <th class="px-3 py-2 text-center">Metode</th>

      <th class="px-3 py-2 text-right">Harga Sebelum</th> {{-- NEW --}}
      <th class="px-3 py-2 text-right">Profit</th>        {{-- NEW --}}

      <th class="px-3 py-2 text-right">Total</th>
      <th class="px-3 py-2 text-right">Dibayar</th>
      <th class="px-3 py-2 text-right">Kembalian</th>
      <th class="px-3 py-2">Tanggal</th>
    </tr>
  </thead>
  <tbody>
    @php($gTotal = 0)
    @php($gModal = 0)   {{-- NEW --}}
    @php($gProfit = 0)  {{-- NEW --}}
    @forelse($data as $row)
      @php($gTotal  += (int)($row->total ?? 0))
      @php($gModal  += (int)($row->total_modal ?? 0))
      @php($gProfit += (int)($row->total_profit ?? 0))
      <tr class="border-t">
        <td class="px-3 py-2">{{ $row->id_Transaction }}</td>
        <td class="px-3 py-2">{{ $row->user->name ?? '-' }}</td>
        <td class="px-3 py-2 text-center">{{ $row->paymentMethod->name_payment ?? '-' }}</td>

        <td class="px-3 py-2 text-right">Rp {{ number_format($row->total_modal ?? 0,0,',','.') }}</td>
        <td class="px-3 py-2 text-right">Rp {{ number_format($row->total_profit ?? 0,0,',','.') }}</td>

        <td class="px-3 py-2 text-right">Rp {{ number_format($row->total,0,',','.') }}</td>
        <td class="px-3 py-2 text-right">Rp {{ number_format($row->paid,0,',','.') }}</td>
        <td class="px-3 py-2 text-right">Rp {{ number_format($row->change,0,',','.') }}</td>
        <td class="px-3 py-2">
          {{ optional($row->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') }}
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="9" class="px-3 py-6 text-center text-gray-500">Tidak ada data.</td>
      </tr>
    @endforelse
  </tbody>
  <tfoot>
    <tr class="border-t font-semibold bg-gray-50">
      <td colspan="3" class="px-3 py-2 text-right">Grand Total</td>
      <td class="px-3 py-2 text-right">Rp {{ number_format($gModal,0,',','.') }}</td>   {{-- Harga Sebelum --}}
      <td class="px-3 py-2 text-right">Rp {{ number_format($gProfit,0,',','.') }}</td>  {{-- Profit --}}
      <td class="px-3 py-2 text-right">Rp {{ number_format($gTotal,0,',','.') }}</td>   {{-- Omzet --}}
      <td colspan="3"></td>
    </tr>
  </tfoot>
</table>
