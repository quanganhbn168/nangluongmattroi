<?php
namespace App\Providers;

// Import các model và observer
use App\Models\Category;
use App\Observers\CategoryObserver;
use App\Models\PostCategory;
use App\Observers\PostCategoryObserver;
use App\Models\ServiceCategory;
use App\Observers\ServiceCategoryObserver;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
// ...

class EventServiceProvider extends ServiceProvider
{
    /**
     * The model observers for your application.
     *
     * @var array
     */
    protected $observers = [
        Category::class => [CategoryObserver::class],
        PostCategory::class => [PostCategoryObserver::class],
        ServiceCategory::class => [ServiceCategoryObserver::class],
    ];

}