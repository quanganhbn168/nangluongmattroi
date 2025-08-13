<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\View\Composers\CartComposer; // <-- Thêm dòng này
use App\Http\View\Composers\MenuComposer;
class ViewServiceProvider extends ServiceProvider
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
        View::composer('partials.frontend.header', CartComposer::class);
        View::composer('partials.frontend.header', MenuComposer::class);
    }
}