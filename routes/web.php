<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\ProductPurchasedController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/home', [HomeController::class, 'index']);
Route::get('/', function () {
    return view('welcome');
});



Route::post('/purchase/{productId}', [ProductPurchasedController::class, 'purchaseProduct'])->name('purchase.product');
Route::get('/login',[AdminController::class,'login'] )->name('login');
Route::get('/admin-dashboard',[AdminController::class,'index'] )->name('dashboard');

Route::middleware('check.admin')->group(function () {



    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/ajax', [ProductController::class, 'ajaxIndex'])->name('products.ajax');
    Route::get('/create-product',[ProductController::class,'create'] )->name('products.create');

   Route::post('product-store',[ProductController::class,'store'] )->name('products.store');
   Route::get('/edit-product/{id}',[ProductController::class,'edit'] )->name('products.edit');
    Route::post('/product/{id}',[ProductController::class,'update'] )->name('products.update');
    Route::get('/delete-product/{id}',[ProductController::class,'destroy'] )->name('products.destroy');
});


Route::post('/import-products', [ProductController::class, 'importProducts'])->name('import.product');
Route::get('/export-products', [ProductController::class, 'exportProducts'])->name('export.products');
Route::get('/import-form', [ProductController::class, 'import'])->name('import.form');
