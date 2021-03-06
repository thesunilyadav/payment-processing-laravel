<?php

use App\Http\Controllers\PaymentController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix'=>"payments"],function(){
    Route::post('/pay',[PaymentController::class,'pay'])->name('pay');
    Route::get('/approval',[PaymentController::class,'approval'])->name('approval');
    Route::get('/cancelled',[PaymentController::class,'cancelled'])->name('cancelled');
});