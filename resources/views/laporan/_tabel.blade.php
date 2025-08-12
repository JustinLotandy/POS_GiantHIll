<table class="min-w-full text-sm">
  <thead class="bg-gray-50">
    <tr>
      <th class="px-3 py-2 text-left">ID</th>
      <th class="px-3 py-2 text-left">User</th>
      <th class="px-3 py-2 text-center">Metode</th>
      <th class="px-3 py-2 text-right">Total</th>
      <th class="px-3 py-2 text-right">Dibayar</th>
      <th class="px-3 py-2 text-right">Kembalian</th>
      <th class="px-3 py-2">Tanggal</th>
    </tr>
  </thead>
  <tbody>
    @php($gTotal = 0)
    @forelse($data as $row)
      @php($gTotal += $row->total)
      <tr class="border-t">
        <td class="px-3 py-2">{{ $row->id }}</td>
        <td class="px-3 py-2">{{ $row->user->name ?? '-' }}</td>
        <td class="px-3 py-2 text-center">{{ $row->paymentMethod->name_payment ?? '-' }}</td>
        <td class="px-3 py-2 text-right">Rp {{ number_format($row->total,0,',','.') }}</td>
        <td class="px-3 py-2 text-right">Rp {{ number_format($row->paid,0,',','.') }}</td>
        <td class="px-3 py-2 text-right">Rp {{ number_format($row->change,0,',','.') }}</td>
        <td class="px-3 py-2">{{ $row->created_at->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="7" class="px-3 py-6 text-center text-gray-500">Tidak ada data.</td>
      </tr>
    @endforelse
  </tbody>
  <tfoot>
    <tr class="border-t font-semibold bg-gray-50">
      <td colspan="3" class="px-3 py-2 text-right">Grand Total</td>
      <td class="px-3 py-2 text-right">Rp {{ number_format($gTotal,0,',','.') }}</td>
      <td colspan="3"></td>
    </tr>
  </tfoot>
</table>
