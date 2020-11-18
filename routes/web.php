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

// タスク一覧画面表示
Route::get('/projects/{project_id}/tasks', 'TaskController@index')->name('tasks.index');
// プロジェクト作成画面表示
Route::get('/projects/create', 'ProjectController@showCreateForm')->name('projects.create');
// プロジェクト作成
Route::post('/projects/create', 'ProjectController@create');
// タスク登録画面画面表示
Route::get('/projects/{project_id}/tasks/create', 'TaskController@showCreateForm')->name('tasks.create');
// タスク作成
Route::post('/projects/{project_id}/tasks/create', 'TaskController@create');



// 初回アクセス時はLaravel側でリクエストを受けてapp.blade.phpを表示
// 2回目以降はフロント側のVueRouterでルーティング
Route::get('/{any}', function () {
    return view('layouts.app');
})->where('any', '.*');

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
