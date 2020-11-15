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

// Route::get('/', function () {
//     return view('welcome');
// });

// 初回アクセス時はLaravel側でリクエストを受けてapp.blade.phpを表示
// 2回目以降はフロント側のVueRouterでルーティング
Route::get('/{any}', function () {
    return view('layouts.app');
})->where('any', '.*');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
