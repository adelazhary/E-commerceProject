<?php

use App\Livewire\CategoriesList;
use App\Livewire\Discounts;
use App\Livewire\InventoryList;
use App\Livewire\OrderForm;
use App\Livewire\OrdersList;
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

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
// routes/web.php
Route::get('/register', 'RegistrationController@showRegistrationForm')->name('register');
require __DIR__.'/auth.php';
route::group(['middleware' => ['auth']], function () {
    Route::get('categories', CategoriesList::class)->name('categories.index');
    Route::get('products', ProductsList::class)->name('products.index');

    Route::get('orders', OrdersList::class)->name('orders.index');
    Route::get('orders/create', OrderForm::class)->name('orders.create');
    Route::get('orders/{order}', OrderForm::class)->name('orders.edit');

    Route::get('products/create', ProductForm::class)->name('products.create');
    Route::get('products/{product}/edit', ProductForm::class)->name('products.edit');

    Route::get('discounts', Discounts::class)->name('discounts.index');
    route::get('inventories', InventoryList::class)->name('inventories.index');

});
