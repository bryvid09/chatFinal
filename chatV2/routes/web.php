<?php

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

use Illuminate\Support\Facades\Route;

Route::any('/','chatController@verIndex')->name('home');
Route::any('/inicio','chatController@ingresar')->name('entrar');
Route::any('/chat','chatController@enviarMensaje')->name('insertar');
Route::get('/privado/{user}','chatController@verPrivado')->name('privado');
Route::any('/privado/mensaje/{user}','chatController@enviarPrivado')->name('mensaje');
Route::get('/salir','chatController@salir')->name('logOut');

