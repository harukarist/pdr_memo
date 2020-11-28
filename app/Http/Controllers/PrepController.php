<?php

namespace App\Http\Controllers;

use App\Prep;
use App\Task;
use App\Project;
use App\Category;
use App\Http\Requests\EditPrep;
use App\Http\Requests\CreatePrep;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PrepController extends Controller
{
    // ステータスの定義
    const CATEGORY = [
        1 => ['id' => 1, 'category_name' => 'Input', 'category_class' => 'badge-light'],
        2 => ['id' => 2, 'category_name' => 'Output', 'category_class' => 'badge-light'],
        3 => ['id' => 3, 'category_name' => 'Etc', 'category_class' => 'badge-light'],
    ];

    // Prep登録画面を表示
    public function showCreateForm(int $project_id, int $task_id)
    {
        // ログインユーザーに紐づくタスク、カテゴリーを取得
        $tasks = Auth::user()->tasks()->get();
        $current_task = Auth::user()->tasks()->find($task_id);
        $categories = self::CATEGORY;

        $unit_times = ['45', '60', '5', '15'];
        $estimated_steps = [2, 3, 4, 5];

        return view('preps.create', compact('tasks', 'current_task', 'categories', 'unit_times', 'estimated_steps'));
    }

    // Prep登録処理
    public function create(int $project_id, int $task_id, CreatePrep $request)
    {
        $prep = new Prep();
        $prep->task_id = $task_id;
        Auth::user()->preps()->save($prep->fill($request->all()));

        // 一覧画面にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', '計画を登録しました');
    }

    // Prep編集画面を表示
    public function showEditForm(int $project_id, int $task_id, int $prep_id)
    {
        // ログインユーザーに紐づく該当IDのレコードを取得
        $current_task = Auth::user()->tasks()->find($task_id);
        $editing_prep = $current_task->preps()->find($prep_id);

        // ログインユーザーに紐づくタスク、カテゴリーを入力フォーム用に取得
        $categories = self::CATEGORY;
        $unit_times = ['30', '45', '60', '5', '15'];
        $estimated_steps = [1, 2, 3, 4, 5];

        return view('preps.edit', compact('editing_prep', 'categories', 'unit_times', 'estimated_steps', 'current_task'));
    }

    // Prep編集処理
    public function edit(int $project_id, int $task_id, int $prep_id, EditPrep $request)
    {
        // リクエストのIDからPrepデータを取得
        $editing_prep = Auth::user()->preps()->find($prep_id);

        // 該当のPrepデータをフォームの入力値で書き換えて保存
        Auth::user()->preps()->save($editing_prep->fill($request->all()));

        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', '計画を変更しました');
    }

    // Prep削除処理
    public function delete(int $project_id, int $task_id, int $prep_id)
    {
        // リクエストで受け取ったIDのPrepを削除
        Auth::user()->tasks()->find($task_id)->preps()->find($prep_id)->delete();
        // Prep::find($prep_id)->delete();
        // Prep::destroy($prep_id);

        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', '計画を削除しました');
    }

    // Do画面表示
    public function showDoForm(int $project_id, int $task_id, int $prep_id)
    {
        // ログインユーザーに紐づく該当IDのPrepを取得
        // $do_prep = Auth::user()->tasks()->find($task_id)->prep()->find($prep_id);
        $current_task = Auth::user()->tasks()->find($task_id);
        $do_prep = $current_task->preps()->find($prep_id);

        // 該当タスクのステータスを着手中に変更
        $current_task->update(['status' => 3]);

        // 該当Prepに紐づくReviewの個数をカウント
        $review_count = $do_prep->reviews->max('step_counter') + 1;
        // dd($do_prep,$current_task, $review_count);

        // ログインユーザーに紐づくタスク、カテゴリーを入力フォーム用に取得
        return view('preps.do', compact('do_prep', 'current_task', 'review_count'));
    }

    // Do完了処理
    public function done(int $project_id, int $task_id, int $prep_id)
    {
        // 該当タスクの実行カウンタを1インクリメント
        Auth::user()->tasks()->find($task_id)->increment('done_count');

        // 削除対象のPrepが属するPrepのPrep一覧にリダイレクト
        return redirect()->route('reviews.create', ['project_id' => $project_id, 'task_id' => $task_id, 'prep_id' => $prep_id])->with('flash_message', 'ステップを1つ達成しました！振り返りを行いましょう！');
    }
}
