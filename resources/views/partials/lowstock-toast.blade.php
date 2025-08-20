@if(($lowStockCount ?? 0) > 0)
<div
  x-data="{ show: {{ session('just_logged_in') ? 'true' : 'false' }} }"
  x-init="if (show) setTimeout(() => show = false, 5000);"
  x-show="show || $store.toast.hover"
  x-cloak
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0 translate-y-2"
  x-transition:enter-end="opacity-100 translate-y-0"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100 translate-y-0"
  x-transition:leave-end="opacity-0 translate-y-2"
  class="fixed top-16 right-6 z-[1000] w-96 max-w-[95vw] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg"
  @mouseenter="$store.toast.hover = true"
  @mouseleave="$store.toast.hover = false"
>
  <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
    <div class="font-semibold text-gray-800 dark:text-gray-100">📦 Notifikasi Stok Rendah</div>
    <button class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" @click="show=false">✕</button>
  </div>

  <div class="px-4 py-3">
    <div class="text-sm text-gray-700 dark:text-gray-300 mb-2">
      {{ $lowStockCount }} produk stok &lt; 15. Beberapa di antaranya:
    </div>

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

    <div class="mt-3 text-[11px] text-gray-500 dark:text-gray-400">Hover ikon 🔔 untuk menampilkan kembali.</div>
  </div>
</div>
@endif
