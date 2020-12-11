<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// ログインユーザーを返却
// Route::get('/user', fn() => Auth::user())->name('user');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/list', 'Api\TaskListController@list');

// タスク完了
Route::put('/tasks/{task_id}/done', 'Api\TaskListController@done');
// タスク未完了
Route::put('/tasks/{task_id}/undone', 'Api\TaskListController@undone');
// タスク削除
Route::delete('/tasks/{task_id}/delete', 'Api\TaskListController@delete');
// 優先度を変更
Route::put('/tasks/{task_id}/priority/{priority_level}', 'Api\TaskListController@changePriority');
Route::put('/tasks/{task_id}/edit', 'Api\TaskListController@edit');
Route::put('/tasks/{task_id}/changeDueDate', 'Api\TaskListController@changeDueDate');


// Route::get('/projects/{project_id}/tasks', 'TaskListController@index');
