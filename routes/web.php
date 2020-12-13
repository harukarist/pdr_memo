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

// 初回アクセス時はLaravel側でリクエストを受けてapp.blade.phpを表示
// 2回目以降はフロント側のVueRouterでルーティング
// Route::get('/{any}', function () {
//     return view('layouts.app');
// })->where('any', '.*');

Route::get('/', function () {
    if (empty(Auth::user())) {
        return view('guest');
    } else {
        return redirect('/home');
    }
});

Route::group(['middleware' => 'auth'], function () {
    // ホーム画面
    Route::get('/home', 'HomeController@index')->name('home');


    // 記録一覧
    Route::get('/reports/weekly', 'ReportController@showWeekly')->name('reports.weekly');
    Route::get('/reports/daily', 'ReportController@showDaily')->name('reports.daily');
    Route::get('/reports/calendar', 'ReportController@showCalendar')->name('reports.calendar');
    // 記録の追加
    Route::get('/records/add', 'RecordController@showAddForm')->name('records.add');
    Route::post('/records/add', 'RecordController@add');
    // 記録の編集
    Route::get('/records/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/reviews/{review_id}/edit', 'RecordController@showEditForm')->name('records.edit');
    Route::patch('/records/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/reviews/{review_id}/edit', 'RecordController@edit');
    // 記録の削除
    Route::delete('/records/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/reviews/{review_id}/delete', 'RecordController@delete')->name('records.delete');

    // TaskController
    // タスク一覧
    Route::get('/projects/{project_id}/tasks', 'TaskController@index')->name('tasks.index');
    Route::get('/projects/{project_id}/tasks/done', 'TaskController@index')->name('tasks.done');
    Route::get('/projects/{project_id}/tasks/all', 'TaskController@index')->name('tasks.all');
    // タスク検索
    Route::get('/projects/{project_id}/tasks/search', 'TaskController@index')->name('tasks.search');

    // 完了済みタスク一覧
    Route::get('/projects/{project_id}/tasks/done', 'TaskController@index')->name('tasks.done');
    // タスク作成
    Route::post('/projects/{project_id}/tasks', 'TaskController@create')->name('tasks.create');
    // タスク編集
    Route::get('/projects/{project_id}/tasks/{task_id}/edit', 'TaskController@showEditForm')->name('tasks.edit');
    Route::patch('/projects/{project_id}/tasks/{task_id}/edit', 'TaskController@edit');
    // タスク削除
    Route::delete('/projects/{project_id}/tasks/{task_id}/delete', 'TaskController@delete')->name('tasks.delete');

    // CategoryController
    // カテゴリー作成
    Route::get('/categories/create', 'CategoryController@showCreateForm')->name('categories.create');
    Route::post('/categories/create', 'CategoryController@create');
    // カテゴリー編集
    Route::get('/categories/{category_id}/edit', 'CategoryController@showEditForm')->name('categories.edit');
    Route::patch('/categories/{category_id}/edit', 'CategoryController@edit');
    // カテゴリー削除
    Route::delete('/categories/{category_id}/delete', 'CategoryController@delete')->name('categories.delete');

    // ProjectController
    // プロジェクト作成
    Route::get('/projects/create', 'ProjectController@showCreateForm')->name('projects.create');
    Route::post('/projects/post', 'ProjectController@post')->name('projects.post');
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
    Route::post('/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/done', 'PrepController@done')->name('preps.done');

    // ReviewController
    // Review作成
    Route::get('/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/reviews/create', 'ReviewController@showCreateForm')->name('reviews.create');
    Route::post('/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/reviews/create', 'ReviewController@create');
    // Review編集
    Route::get('/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/reviews/{review_id}/edit', 'ReviewController@showEditForm')->name('reviews.edit');
    Route::patch('/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/reviews/{review_id}/edit', 'ReviewController@edit');
    // Review削除
    Route::delete('/projects/{project_id}/tasks/{task_id}/preps/{prep_id}/reviews/{review_id}/delete', 'ReviewController@delete')->name('reviews.delete');

    // プロフィール変更
    Route::get('/profile', 'ChangeProfileController@showChangeProfileForm')->name('profile.change');
    Route::patch('/profile', 'ChangeProfileController@changeProfile');
    // メールアドレス変更
    Route::get('/email', 'ChangeEmailController@showChangeEmailForm')->name('email.change');
    Route::patch('/email', 'ChangeEmailController@sendChangeEmailLink');
    // 新規メールアドレスに更新
    Route::get("reset/{token}", "ChangeEmailController@reset");
});

// 会員登録・ログイン・ログアウト・パスワード再設定
Auth::routes();
