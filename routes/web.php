<?php

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
Route::resource('plant','Plancontroller');

Route::post('add/plant', 'Plancontroller@addPlant')->name('addPlant');
Route::post('add/product', 'Plancontroller@addProduct')->name('addProduct');

//route pake template
Route::get('/tamplate', 'Plancontroller@tamplate')->name('tamplate');
Route::view('/layout','layout');
