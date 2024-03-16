<?php

use App\Http\Controllers\ProductController;
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
use App\Livewire\UploadImage;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

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
    Route::get('products/', ProductsList::class)->name('products.index.list');

    Route::get('orders', OrdersList::class)->name('orders.index');
    Route::get('orders/create', OrderForm::class)->name('orders.create');
    Route::get('orders/{order}', OrderForm::class)->name('orders.edit');

    Route::get('products/create', ProductForm::class)->name('products.create');
    Route::get('products/{product}/edit', ProductForm::class)->name('products.edit');

    Route::get('products/{id}', ProductDetails::class)->name('product.show');

    Route::get('discounts', Discounts::class)->name('discounts.index');
    Route::get('discount/create', DiscountForm::class)->name('discounts.create');

    Route::get('cart/add')->name('cart.add');
    // routes/web.php
    Route::get('/login/google', function () {
        return Socialite::driver('google')->redirect();
    });
    Route::get('/login/google/callback', function () {
        $user = Socialite::driver('google')->user();
        // Process the user data and login/register the user
        return "You're logged in using Google! (user data: $user)";
    });
    Route::get('/login/github', function () {
        return Socialite::driver('github')->redirect();
    });
    Route::get('/login/github/callback', function () {
        $user = Socialite::driver('github')->user();
        // Process the user data and login/register the user
        return "You're logged in using Github! (user data: $user)";
    });
});

Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
});

Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();

    // $user->token
});
