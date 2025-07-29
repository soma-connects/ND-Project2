<?php
use App\Http\Controllers\Admin\CartController as AdminCartController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AuthManager;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\UserCartController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/run-storage-link', function () {
    try {
        Artisan::call('storage:link');
        return 'Storage link created successfully.';
    } catch (\Exception $e) {
        return 'Failed to create storage link: ' . $e->getMessage();
    }
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cap', [HomeController::class, 'cap'])->name('cap');
Route::get('/sheet', [HomeController::class, 'sheet'])->name('sheet');
Route::get('/shroom', [HomeController::class, 'shroom'])->name('shroom');
Route::get('/about-us', [HomeController::class, 'aboutus'])->name('aboutus');
Route::get('/contact-us', [HomeController::class, 'contactus'])->name('contactus');
Route::post('/contact-us', [HomeController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/product/{id}', [HomeController::class, 'product'])->name('product');
Route::post('/newsletter/subscribe', [HomeController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Authentication Routes
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [AuthManager::class, 'loginpost'])->name('login.post');
Route::get('/signup', [SignupController::class, 'create'])->name('signup');
Route::post('/signup', [AuthManager::class, 'createpost'])->name('signup.post');
Route::post('/logout', [AuthManager::class, 'logout'])->name('logout');
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/cart/store-order', [CartController::class, 'storeOrder'])->name('cart.storeOrder');
Route::get('/order/confirmation/{orderId}', [CartController::class, 'confirmation'])->name('order.confirmation');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::get('/cart/summary', [CartController::class, 'summary'])->name('cart.summary');

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
     Route::resource('products', ProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);
    Route::get('/carts', [CartController::class, 'index'])->name('admin.carts.index');
    Route::get('/user-carts', [UserCartController::class, 'index'])->name('admin.user-carts.index');
    Route::get('/user-carts/{user}', [UserCartController::class, 'show'])->name('admin.user-carts.show');
    Route::get('/images', [ImageController::class, 'index'])->name('admin.images.index');
    Route::post('/images', [ImageController::class, 'store'])->name('admin.images.store');
    Route::delete('/images/{image}', [ImageController::class, 'destroy'])->name('admin.images.destroy');
    Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
    Route::get('/payments/{receipt}', [PaymentController::class, 'show'])->name('admin.payments.show');
    Route::post('/payments/{receipt}', [PaymentController::class, 'verify'])->name('admin.payments.verify');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::delete('/user-carts/{cart}', [UserCartController::class, 'destroy'])->name('admin.user-carts.remove');
});

