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
//This Route for if you are using VueJs Routes Redirections
Route::get('{any}', function () {
    return view('welcome');
})->where('any','.*');

//This Route for if you are using ReactJs Routes Redirections
Route::any('{path?}', function () {
    return view('react');
})->where('path', '.+');

// Route::get('/', function () {
//     return view('welcome');
// });
