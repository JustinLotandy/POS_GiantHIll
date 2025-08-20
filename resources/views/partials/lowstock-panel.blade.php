<div>
  <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
    <div class="font-semibold text-gray-800 dark:text-gray-100">
      Notifikasi Stok Rendah
    </div>
    @isset($closable)
      <button class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
              @click="{{ $closable }}">✕</button>
    @endisset
  </div>

  <div class="px-4 py-3">
    <div class="text-sm text-gray-700 dark:text-gray-300 mb-2">
      {{ $lowStockCount }} produk stok &lt; 15. Beberapa di antaranya:
    </div>

    @if(($lowStockCount ?? 0) === 0)
      <div class="text-sm text-gray-500 dark:text-gray-400">Semua aman 🎉</div>
    @else
      <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 max-h-44 overflow-auto">
        @foreach(($lowStocks ?? []) as $p)
          @if(is_object($p))
            <li class="flex justify-between">
              <span>{{ $p->name }}</span>
              <span class="ml-2 text-xs px-2 py-0.5 rounded-full
                @if($p->stock <= 0) bg-red-600 text-white
                @elseif($p->stock < 5) bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300
                @else bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 @endif">
                Stok: {{ $p->stock }}
              </span>
            </li>
          @endif
        @endforeach
      </ul>
      <div class="mt-2 text-[11px] text-gray-500 dark:text-gray-400">
        Menampilkan hingga 10 produk stok terendah.
      </div>
    @endif
  </div>
</div>
