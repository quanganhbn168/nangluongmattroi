<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\SlugObserver;

class SlugServiceProvider extends ServiceProvider
{
    /**
     * Danh sách các model cần tự động tạo slug
     * @var array
     */
    protected $observableModels = [
        \App\Models\Product::class,
        \App\Models\Category::class,
        \App\Models\Service::class,
        \App\Models\Project::class,
        \App\Models\ServiceCategory::class,
        \App\Models\PostCategory::class,
        \App\Models\Post::class,
        // Thêm các model khác tại đây
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        foreach ($this->observableModels as $model) {
            $model::observe(SlugObserver::class);
        }
    }
}