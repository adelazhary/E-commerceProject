<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\SocialLoginController;
use App\Livewire\CategoriesList;
use App\Livewire\DiscountForm;
use App\Livewire\Discounts;
use App\Livewire\Image;
use App\Livewire\ImageUplaod;
use App\Livewire\OrderForm;
use App\Livewire\OrdersList;
use App\Livewire\ProductDetails;
use App\Livewire\ProductForm;
use App\Livewire\ProductsList;
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

Route::view('/', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// routes/web.php
Route::get('/register', 'RegistrationController@showRegistrationForm')->name('register');
require __DIR__ . '/auth.php';

route::group(['middleware' => ['auth', 'throttle:rate_limit,10']], function () {
    Route::view('profile', 'profile')
        ->name('profile');

    Route::get('categories', CategoriesList::class)->name('categories.index');

    /* These routes are defining the following actions related to orders: */

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', OrdersList::class)->name('index');
        Route::get('/create', OrderForm::class)->name('create');
        Route::get('/{order}', OrderForm::class)->name('edit');
    });

    /* These routes are defining the actions related to products in the application: */

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', ProductsList::class)->name('index.list');
        Route::get('/{product}/edit', ProductForm::class)->name('edit');
        Route::get('/{id}', ProductDetails::class)->name('show');
        Route::get('/create', ProductForm::class)->name('create');
    });
    /* These routes are defining the actions related to discounts in the application. */
    Route::prefix('discounts')->name('discounts.')->group(function () {
        Route::get('/', Discounts::class)->name('index');
        Route::get('/create', DiscountForm::class)->name('create');
        Route::get('/{discount}', DiscountForm::class)->name('edit');
    });

    Route::get('cart/add')->name('cart.add');
});
/* This block of code is defining routes for social login functionality using OAuth providers like
Google and GitHub. Here's a breakdown of what each part is doing: */

Route::controller(SocialiteController::class)->prefix('/auth/{driver}/')->name('socialite.')->group(function () {

    Route::get('redirect', 'redirect')
        ->name('redirect')
        ->whereIn('driver', ['google|github']);

    Route::get('callback', 'callback')
        ->name('callback')
        ->whereIn('driver', ['google|github']);
});
