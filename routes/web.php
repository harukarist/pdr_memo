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

Route::group(['middleware' => 'auth'], function () {
    // ホーム画面
    Route::get('/', 'HomeController@index')->name('home');
    // タスク一覧
    Route::get('/projects/{project_id}/tasks', 'TaskController@index')->name('tasks.index');

    // プロジェクト作成
    Route::get('/projects/create', 'ProjectController@showCreateForm')->name('projects.create');
    Route::post('/projects/create', 'ProjectController@create');
    // プロジェクト編集
    Route::get('/projects/{project_id}/edit', 'ProjectController@showEditForm')->name('projects.edit');
    Route::post('/projects/{project_id}/edit', 'ProjectController@edit');
    // プロジェクト削除
    Route::get('/projects/{project_id}/delete', 'ProjectController@delete')->name('projects.delete');


    // タスク登録画面画面表示
    Route::get('/projects/{project_id}/tasks/create', 'TaskController@showCreateForm')->name('tasks.create');
    // タスク作成処理
    Route::post('/projects/{project_id}/tasks/create', 'TaskController@create');
    // タスク編集画面
    Route::get('/projects/{project_id}/tasks/{task_id}/edit', 'TaskController@showEditForm')->name('tasks.edit');
    // タスク編集処理
    Route::post('/projects/{project_id}/tasks/{task_id}/edit', 'TaskController@edit');
    // タスク削除処理
    Route::get('/projects/{project_id}/tasks/{task_id}/delete', 'TaskController@delete')->name('tasks.delete');

    // RecordController
    // 記録一覧画面
    Route::get('/records', 'RecordController@index')->name('records.index');

    // PrepController
    // Prep作成画面表示
    Route::get('/preps/create', 'PrepController@showCreateForm')->name('preps.create');
    // Prep登録
    Route::post('/preps/create', 'PrepController@create');
    // Prep編集画面
    Route::get('/preps/{prep_id}/edit', 'PrepController@showEditForm')->name('preps.edit');
    // Prep編集処理
    Route::post('/preps/{prep_id}/edit', 'PrepController@edit');
    // Prep削除処理
    Route::post('/preps/{prep_id}/delete', 'PrepController@delete')->name('preps.delete');
    // Do画面
    Route::get('/preps/{prep_id}/do', 'PrepController@showDoForm')->name('preps.do');
    // Do送信処理
    Route::post('/preps/{prep_id}/do', 'PrepController@done');
});

// 会員登録・ログイン・ログアウト・パスワード再設定
Auth::routes();

// // 初回アクセス時はLaravel側でリクエストを受けてapp.blade.phpを表示
// // 2回目以降はフロント側のVueRouterでルーティング
// Route::get('/{any}', function () {
//     return view('layouts.app');
// })->where('any', '.*');
