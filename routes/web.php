<?php

use App\Events\MessageEvent;
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

Route::get('/storage', function () {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    dd("storage");
});



Route::get('/message/created', function () {
    MessageEvent::dispatch('lorem ipsum anjay');
});
