<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Product;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Kirim data low-stock ke navbar dan partial-panel/toast
        View::composer(['layouts.navigation', 'partials.lowstock-panel', 'partials.lowstock-toast'], function ($view) {
            $q = Product::where('stock', '<', 15)->orderBy('stock', 'asc');

            $lowStockCount = (clone $q)->count();
            $lowStocks = (clone $q)->limit(10)->get(['id_Produk','name','stock']);

            $view->with(compact('lowStockCount', 'lowStocks'));
        });
    }
}
