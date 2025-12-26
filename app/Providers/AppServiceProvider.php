<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('layouts.admin-sidebar', function ($view) {
            $view->with([
                'total_products' => \App\Models\Product::count(),
                'total_orders' => \App\Models\Order::count(),
                'total_users' => \App\Models\User::count(),
                'pending_reviews' => \App\Models\Review::where('is_approved', false)->count(),
            ]);
        });

        \Illuminate\Support\Facades\View::composer('layouts.user-sidebar', function ($view) {
            $user = auth()->user();
            if ($user) {
                $view->with([
                    'total_orders' => $user->orders()->count(),
                    'wishlist_count' => $user->wishlists()->count(),
                ]);
            }
        });
    }
}
