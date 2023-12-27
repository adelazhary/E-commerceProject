<?php

use App\Livewire\CategoriesList;
use App\Livewire\Category;
use App\Livewire\ProductForm;
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

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::controller(ProductForm::class)->prefix('products')->name('products.')->group(function () {
    Route::get('/create', 'create');
    Route::post('/{product}', 'update');
});
Route::get('categories', CategoriesList::class)->name('categories.index');
// Route::get('products/{product}', ProductForm::class)->name('products.edit');
// Route::get('categories', Category::class)->name('categories.index');
// Route::controller(Category::class)->group(function () {
//     Route::get('categories', 'render')->name('categories.index');
//     Route::post('/orders', 'store');
// });
