<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\IntroController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\TagController;

// use App\Http\Controllers\ToggleController;

Route::middleware(['auth:admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::post('/toggle', [DashboardController::class, 'toggleField'])->name('toggle');
    Route::resource('intros', IntroController::class);

    Route::resource('categories', CategoryController::class);
    Route::post('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
      ->name('categories.toggle-status');
    Route::resource('products', ProductController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('post-categories', PostCategoryController::class);
    Route::resource('posts', PostController::class);
    Route::resource('slides', SlideController::class);
    Route::post('/slides/{slide}/toggle-status', [SlideController::class, 'toggleStatus'])
      ->name('slides.toggle-status');
    Route::resource('projects', ProjectController::class);
    Route::resource('service_categories', ServiceCategoryController::class);
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::resource('contacts', ContactController::class)->only(['index', 'destroy']);
    Route::resource('teams', TeamController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::resource('orders', OrderController::class)->only([
        'index', 'show', 'destroy'
    ]);

    Route::resource('brands', BrandController::class)->except('show');
    Route::get('ajax/brands', [BrandController::class, 'ajax'])->name('ajax.brands');

    Route::resource('tags', TagController::class)->except('show');
    Route::get('ajax/tags', [TagController::class, 'ajax'])->name('ajax.tags');
    
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::resource('branches', BranchController::class);
});
