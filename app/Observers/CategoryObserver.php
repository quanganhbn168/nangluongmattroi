<?php
namespace App\Observers;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    public function saved(Category $category): void {
        Cache::forget('shared_categories_menu');
    }
    public function deleted(Category $category): void {
        Cache::forget('shared_categories_menu');
    }
}