<?php
namespace App\Observers;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Cache;

class ServiceCategoryObserver
{
    public function saved(ServiceCategory $serviceCategory): void {
        Cache::forget('shared_service_categories_menu');
    }
    public function deleted(ServiceCategory $serviceCategory): void {
        Cache::forget('shared_service_categories_menu');
    }
}