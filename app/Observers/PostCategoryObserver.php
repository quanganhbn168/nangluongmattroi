<?php
namespace App\Observers;
use App\Models\PostCategory;
use Illuminate\Support\Facades\Cache;

class PostCategoryObserver
{
    public function saved(PostCategory $postCategory): void {
        Cache::forget('shared_post_categories_menu');
    }
    public function deleted(PostCategory $postCategory): void {
        Cache::forget('shared_post_categories_menu');
    }
}