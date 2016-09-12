<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use Illuminate\Http\Request;

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/', function () {
    return view('welcome');
});

Route::get('verify_token', 'verifyTokenController@index');

Route::resource('province', 'provinceController', ['only' => [
	'index'
]]);

Route::resource('city', 'cityController', ['only' => [
	'show'
]]);

Route::resource('citiesinprovince', 'citiesinprovinceController', ['only' => [
	'show'
]]);
