<?php

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
