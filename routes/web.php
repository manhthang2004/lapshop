<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

session_start();


Route::get('/', [ProductController::class, 'index'])->name('product.index');
// Show
Route::get('product/{id}/{color_id?}', [ProductController::class, 'show'])->name('product.show');
Route::post('/product/review', [ProductController::class, 'review'])->name('product.review');

Route::post('/comments/submit', [ProductController::class, 'submitComment'])->name('submit_comment');
Route::get('/sanpham/list', [ProductController::class, 'list'])->name('product.list');
Route::post('/sanpham/filter', [ProductController::class, 'filter'])->name('product.filter');

//Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.form');

Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/index', [AuthController::class, 'showAdmin'])->name('admin.index');
    });
});

Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change_password');
Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change_password.submit');


// Cart 
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('cart/updateQuantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
Route::get('/cart/remove/{id_cart}/{color_id}', [CartController::class, 'remove'])->name('cart.remove');



Route::get('/checkout', [CartController::class, 'showCheckout'])->name('cart.checkout');
Route::post('/checkout', [CartController::class, 'processCheckout'])->name('cart.checkout.post');
Route::get('/shipping-process', [CartController::class, 'shippingProcess'])->name('shipping_process');

Route::post('/voucher/apply', [CartController::class, 'applyVoucher'])->name('apply_voucher');


Route::get('/completed-order', [CartController::class, 'completedOrder'])->name('completed_order');
Route::get('/cancelled-order', [CartController::class, 'cancelledOrder'])->name('cancelled_order');
Route::get('/cancel-order/{id}', [CartController::class, 'cancelOrder'])->name('cancel_order');