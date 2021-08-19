<?php

use App\Models\Order;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\OrderPositionController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $orders = Order::all();
    return view('dashboard', ['orders' => $orders ]);
})->middleware(['auth'])->name('dashboard');

Route::resource('/order', OrderController::class)->name('index','order');
Route::resource('/orderPosition', OrderPositionController::class)->name('index','orderPosition');
Route::resource('/product', ProductController::class)->name('index','product');
Route::resource('/client', ClientController::class)->name('index','client');
Route::get('/print/{id}', [OrderController::class, 'createPDF']);
Route::get('/printCMR/{id}', [OrderController::class, 'createCMR']);
Route::post('store/file', [FileUploadController::class, 'store']);
Route::post('addNewAddress', [AddressController::class, 'addNewAddress'])->name('addNewAddress');
require __DIR__.'/auth.php';
