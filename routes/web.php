<?php

use App\Models\Order;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

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



//Route::put('/createCirculateDoc', [AddNewOrderController::class, 'createOrder'])->name('addNewOrder.store');

Route::get('/addClient', function () {
    return view('addClient');
})->middleware(['auth'])->name('addClient');

Route::get('/addProduct', function () {
    return view('addProduct');
})->middleware(['auth'])->name('addProduct');

require __DIR__.'/auth.php';

Route::resource('/createCirculateDoc', OrderController::class)->name('index','createCirculateDoc');
