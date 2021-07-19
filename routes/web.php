<?php

use App\Models\Order;
use Illuminate\Support\Facades\Route;

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

Route::get('/createCirculateDoc', function () {
    return view('createCirculateDoc');
})->middleware(['auth'])->name('createCirculateDoc');

Route::get('/addClient', function () {
    return view('addClient');
})->middleware(['auth'])->name('addClient');

Route::get('/addProduct', function () {
    return view('addProduct');
})->middleware(['auth'])->name('addProduct');

require __DIR__.'/auth.php';
