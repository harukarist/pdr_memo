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
    Route::get('/records', 'RecordController@index')->name('record.index');

    // TaskController
    // タスク一覧
    Route::get('/projects/{project_id}/tasks', 'TaskController@index')->name('tasks.index');
    // タスク作成
    Route::get('/projects/{project_id}/tasks/create', 'TaskController@showCreateForm')->name('tasks.create');
    Route::post('/projects/{project_id}/tasks/create', 'TaskController@create');
    Route::post('/projects/{project_id}/tasks', 'TaskController@taskAdd')->name('tasks.add');
    // タスク編集
    Route::get('/projects/{project_id}/tasks/{task_id}/edit', 'TaskController@showEditForm')->name('tasks.edit');
    Route::patch('/projects/{project_id}/tasks/{task_id}/edit', 'TaskController@edit');
    // タスク削除
    Route::delete('/projects/{project_id}/tasks/{task_id}/delete', 'TaskController@delete')->name('tasks.delete');

    // ProjectController
    // プロジェクト作成
    Route::get('/projects/create', 'ProjectController@showCreateForm')->name('projects.create');
    Route::post('/projects/create', 'ProjectController@create');
    // プロジェクト編集
    Route::get('/projects/{project_id}/edit', 'ProjectController@showEditForm')->name('projects.edit');
    Route::patch('/projects/{project_id}/edit', 'ProjectController@edit');
    // プロジェクト削除
    Route::delete('/projects/{project_id}/delete', 'ProjectController@delete')->name('projects.delete');


    // PrepController
    // Prep作成
    Route::get('/projects/{project_id}/tasks/{task_id}/preps/create', 'PrepController@showCreateForm')->name('preps.create');
    Route::post('/projects/{project_id}/tasks/{task_id}/preps/create', 'PrepController@create');

    // Prep編集
    Route::get('/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/edit', 'PrepController@showEditForm')->name('preps.edit');
    Route::patch('/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/edit', 'PrepController@edit');

    // Prep削除
    Route::delete('/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/delete', 'PrepController@delete')->name('preps.delete');

    // Do
    Route::get('/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/do', 'PrepController@showDoForm')->name('preps.do');
    Route::get('/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/done', 'PrepController@done')->name('preps.done');

    // ReviewController
    // Review作成
    Route::get('/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/reviews/create', 'ReviewController@showCreateForm')->name('reviews.create');
    Route::post('/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/reviews/create', 'ReviewController@create');
    // Review編集
    Route::get('/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/reviews/{review_id}/edit', 'ReviewController@showEditForm')->name('reviews.edit');
    Route::patch('/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/reviews/{review_id}/edit', 'ReviewController@edit');
    // Prep削除
    Route::delete('/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/reviews/{review_id}/delete', 'ReviewController@delete')->name('reviews.delete');
});

// 会員登録・ログイン・ログアウト・パスワード再設定
Auth::routes();

// // 初回アクセス時はLaravel側でリクエストを受けてapp.blade.phpを表示
// // 2回目以降はフロント側のVueRouterでルーティング
// Route::get('/{any}', function () {
//     return view('layouts.app');
// })->where('any', '.*');
