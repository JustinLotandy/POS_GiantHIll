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
        // Notifikasi stok untuk navbar & toast
        View::composer(['layouts.navigation', 'partials.lowstock-toast'], function ($view) {
            $lowStockQuery = Product::where('stock', '<', 15)->orderBy('stock', 'asc');

            $lowStockCount = (clone $lowStockQuery)->count();

            $lowStocks = (clone $lowStockQuery)
                ->limit(10)
                ->get(['id_Produk', 'name', 'stock']);

            $view->with(compact('lowStockCount', 'lowStocks'));
        });
    }
}
