{{-- Tidak perlu Alpine untuk fitur hover ini --}}
<style>
  /* jaga-jaga kalau ada container overflow, pastikan navbar tidak memangkas dropdown */
  nav.navbar-overflow-visible { overflow: visible; }
</style>

<nav class="navbar-overflow-visible bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @can('dashboard.lihat')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endcan

                    @can('pos.transaksi')
                        <x-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')">
                            {{ __('POS') }}
                        </x-nav-link>
                    @endcan

                    @can('products.lihat')
                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                            {{ __('Produk') }}
                        </x-nav-link>
                    @endcan

                    @can('categories.lihat')
                        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                            {{ __('Kategori') }}
                        </x-nav-link>
                    @endcan

                    @can('payment_methods.lihat')
                        <x-nav-link :href="route('payment_methods.index')" :active="request()->routeIs('payment_methods.*')">
                            {{ __('Payment Method') }}
                        </x-nav-link>
                    @endcan

                    @can('transactions.lihat')
                        <x-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')">
                            {{ __('Daftar Transaksi') }}
                        </x-nav-link>
                    @endcan

                    @can('users.lihat')
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            {{ __('Pengguna') }}
                        </x-nav-link>
                    @endcan

                    @canany(['laporan.harian', 'laporan.mingguan', 'laporan.bulanan'])
                        <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                            {{ __('Laporan') }}
                        </x-nav-link>
                    @endcanany

                    @can('roles.lihat')
                        <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.*')">
                            {{ __('Roles') }}
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <div class="flex items-center">
                {{-- ===== Notifikasi stok rendah (MUNCUL DI BAWAH IKON 🔔 SAAT HOVER) ===== --}}
                @php
                    $alertCount = $lowStockCount ?? 0;
                    $hasAlert   = $alertCount > 0;
                @endphp

                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <!-- Wrapper wajib relative + group agar dropdown bisa absolute & group-hover -->
                    <div class="relative group">
                        <!-- Tombol/Ikon Notifikasi -->
                        <button type="button"
                                class="relative inline-flex items-center px-3 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none"
                                aria-label="Notifikasi stok rendah">
                            <span class="{{ $hasAlert ? 'text-red-600' : 'text-gray-600 dark:text-gray-300' }} relative">
                                <span class="text-lg">🔔</span>
                                @if($hasAlert)
                                    <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[11px] leading-none rounded-full px-1.5 min-w-[18px] text-center ring-2 ring-white dark:ring-gray-800">
                                        {{ $alertCount }}
                                    </span>
                                @else
                                    <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-gray-300 rounded-full ring-2 ring-white dark:ring-gray-800"></span>
                                @endif
                            </span>
                        </button>

                        <!-- DROPDOWN: tepat di bawah ikon -->
                        <div
                            class="pointer-events-none group-hover:pointer-events-auto
                                   opacity-0 group-hover:opacity-100 translate-y-0 group-hover:translate-y-1
                                   transition duration-150 ease-out
                                   absolute right-0 top-full mt-2
                                   w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg
                                   border border-gray-200 dark:border-gray-700 p-3 z-50"
                        >
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                    Stok Rendah (&lt; 15)
                                </div>
                                <span class="text-xs text-gray-500">Hover untuk lihat</span>
                            </div>

                            @if(($lowStockCount ?? 0) === 0)
                                <div class="text-sm text-gray-500 dark:text-gray-400">Semua aman 🎉</div>
                            @else
                                <ul class="max-h-64 overflow-auto divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach(($lowStocks ?? []) as $p)
                                        <li class="py-2 flex items-center justify-between">
                                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ is_object($p) ? $p->name : '-' }}
                                            </div>
                                            <span class="text-xs px-2 py-0.5 rounded-full
                                                @if(is_object($p) && $p->stock <= 0) bg-red-600 text-white
                                                @elseif(is_object($p) && $p->stock < 5) bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300
                                                @else bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 @endif">
                                                Stok: {{ is_object($p) ? $p->stock : '-' }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="mt-2 text-[11px] text-gray-500 dark:text-gray-400">
                                    Menampilkan hingga 10 produk stok terendah.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ===== Settings Dropdown: hanya saat login ===== --}}
                @auth
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
                @endauth

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button x-data @click="$el.closest('nav').querySelector('[data-responsive]').classList.toggle('hidden')"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div data-responsive class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                Products
            </x-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                @auth
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                @else
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">Guest</div>
                    <div class="font-medium text-sm text-gray-500">-</div>
                @endauth
            </div>

            <div class="mt-3 space-y-1">
                @auth
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                @else
                    @if (Route::has('login'))
                        <x-responsive-nav-link :href="route('login')">
                            {{ __('Log In') }}
                        </x-responsive-nav-link>
                    @endif
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</nav>
