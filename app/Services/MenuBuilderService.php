<?php
namespace App\Services;

use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class MenuBuilderService
{
    public function getMenuTop()
    {
        return Cache::rememberForever('menu_top', function () {
            $menu = [];

            $menu = array_merge($menu, ServiceCategory::where('is_menu', 1)->get()->map(function ($item) {
                return ['title' => $item->name, 'url' => route('frontend.service_category.detail', $item->slug)];
            })->toArray());

            $menu = array_merge($menu, Service::where('is_menu', 1)->get()->map(function ($item) {
                return ['title' => $item->name, 'url' => route('frontend.service.detail', $item->slug)];
            })->toArray());

            $menu = array_merge($menu, Category::where('is_menu', 1)->get()->map(function ($item) {
                return ['title' => $item->name, 'url' => route('frontend.category.detail', $item->slug)];
            })->toArray());

            $menu = array_merge($menu, Product::where('is_menu', 1)->get()->map(function ($item) {
                return ['title' => $item->name, 'url' => route('frontend.product.detail', $item->slug)];
            })->toArray());

            return $menu;
        });
    }

    public function getMenuFooter()
    {
        return Cache::rememberForever('menu_footer', function () {
            $menu = [];

            $menu = array_merge($menu, ServiceCategory::where('is_footer', 1)->get()->map(function ($item) {
                return ['title' => $item->name, 'url' => route('frontend.service_category.detail', $item->slug)];
            })->toArray());

            $menu = array_merge($menu, Service::where('is_footer', 1)->get()->map(function ($item) {
                return ['title' => $item->name, 'url' => route('frontend.service.detail', $item->slug)];
            })->toArray());

            $menu = array_merge($menu, Category::where('is_footer', 1)->get()->map(function ($item) {
                return ['title' => $item->name, 'url' => route('frontend.category.detail', $item->slug)];
            })->toArray());

            $menu = array_merge($menu, Product::where('is_footer', 1)->get()->map(function ($item) {
                return ['title' => $item->name, 'url' => route('frontend.product.detail', $item->slug)];
            })->toArray());

            return $menu;
        });
    }
}
