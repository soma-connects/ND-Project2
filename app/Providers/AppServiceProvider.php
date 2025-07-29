<?php

namespace App\Providers;

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Database query logger
        DB::listen(function ($query) {
            Log::info('SQL Query', [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time,
            ]);
        });

        // Share cart count with header
        View::composer('components.layouts.header', function ($view) {
            $cartCount = CartController::getCartCountStatic();
            $view->with('cartCount', $cartCount);
        });
    }
}