@if(($lowStockCount ?? 0) > 0)
<div
  x-data="{ show: true }"
  x-init="setTimeout(() => show = false, 5000)"
  x-show="show"
  x-cloak
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0 -translate-y-2"
  x-transition:enter-end="opacity-100 translate-y-0"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100 translate-y-0"
  x-transition:leave-end="opacity-0 -translate-y-2"
  class="fixed top-16 right-5 z-[1000] w-96 max-w-[95vw] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg"
>
  @include('partials.lowstock-panel', ['closable' => 'show=false'])
</div>
@endif
