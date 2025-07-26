<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IntroController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\SlugController;
use UniSharp\LaravelFilemanager\Lfm;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerWelcomeEmail;

/*Route::get('/send-welcome-email', function () {
    $testRecipient = 'quanganhbn168@gmail.com';
    $testCustomerName = 'Khách Hàng VIP';

    try {
        Mail::to($testRecipient)->send(new CustomerWelcomeEmail($testCustomerName));
        return "Email chào mừng đã được gửi thành công tới: " . $testRecipient;
    } catch (\Exception $e) {
        // Hiển thị lỗi nếu có vấn đề
        return "Gửi mail thất bại. Lỗi: " . $e->getMessage();
    }
});*/

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['auth:admin']], function () {
    Lfm::routes();
});
Route::get("/", [HomeController::class,"index"])->name("home"); 
Route::group(['prefix'=>'san-pham'], function(){
    Route::get('/', [ProductController::class,'allProduct'])->name('frontend.allProduct');
    Route::get('/{product:slug}', [ProductController::class,'show'])->name('frontend.product.show');
});
Route::group(["prefix"=>"danh-muc"], function(){
    Route::get("/{postCategory:slug}",[PostController::class,"postByCate"])->name("frontend.post.postByCate");
    Route::get("chi-tiet/{post:slug}",[PostController::class,"detail"])->name("frontend.post.detail");
});
Route::group(["prefix"=>"dich-vu"], function(){
    Route::get("/danh-muc/{slug}", [ServiceController::class,"serviceByCate"])->name("services.serviceByCate");
    Route::get("/{services:slug}", [ServiceController::class,"detail"])->name("services.show");
});
Route::get('/tim-kiem', [HomeController::class, 'search'])->name('frontend.post.search');

Route::get('gioi-thieu', [IntroController::class,'show'])->name('intro.show');
Route::get('lien-he',[ContactController::class,'show'])->name('contact.show');
Route::post('lien-he',[ContactController::class,'store'])->name('contact.store');
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('add', [CartController::class, 'add'])->name('cart.add');
    Route::post('update', [CartController::class, 'update'])->name('cart.update');
    Route::post('remove', [CartController::class, 'remove'])->name('cart.remove');
});
Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('thank-you',function(){
    return view('page/thank-you');
})->name('thank-you');
require __DIR__.'/admin.php';   
require __DIR__.'/auth.php';   
Route::get('/{slug}', [SlugController::class, 'resolve'])->name('slug.resolve');