<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\ServiceCategory;
use App\Models\PostCategory;
use App\Models\Category;

class ViewShareServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer(['frontend.*', 'page.*'], function ($view) {

            $dichvuMenu = collect();
            if (Schema::hasTable('service_categories')) {
                $dichvuMenu = ServiceCategory::with(['children' => fn($q) => $q->where('status', 1)->where('is_menu', 1)])
                    ->where('parent_id', 0)
                    ->where('status', 1)
                    ->where('is_menu', 1)
                    ->get();
            }

            $postMenu = collect();
            if (Schema::hasTable('post_categories')) {
                $postMenu = PostCategory::where('parent_id', 0)
                    ->where('status', 1)
                    ->where('is_menu', 1)
                    ->get();
            }

            $categoriesMenu = collect();
            if (Schema::hasTable('categories')) {
                $categoriesMenu = Category::with(['children' => fn($q) => $q->where('status', 1)->where('is_menu', 1)])
                    ->where('parent_id', 0)
                    ->where('status', 1)
                    ->where('is_menu', 1)
                    ->get();
            }

            $view->with('dichvuMenu', $dichvuMenu)
                 ->with('postMenu', $postMenu)
                 ->with('categoriesMenu', $categoriesMenu);
        });
    }
}
