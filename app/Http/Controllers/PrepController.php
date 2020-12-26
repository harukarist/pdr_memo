<?php

namespace App\Http\Controllers;

use App\Prep;
use Carbon\Carbon;
use App\Http\Requests\EditPrep;
use App\Http\Requests\CreatePrep;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PrepController extends Controller
{

    // Prep登録画面を表示
    public function showCreateForm($project_id, $task_id)
    {
        // パラメータが数字でない場合はリダイレクト
        if (!ctype_digit($project_id . $task_id)) {
            return redirect('home')->with('flash_message', '不正な操作が行われました');
        }

        // ログインユーザーに紐づくタスク、カテゴリーを取得
        $tasks = Auth::user()->tasks()->get();
        $current_task = Auth::user()->tasks()->find($task_id);
        $categories = Auth::user()->categories()->get();

        return view('preps.create', compact('tasks', 'current_task', 'categories'));
    }

    // Prep登録処理
    public function create($project_id, $task_id, CreatePrep $request)
    {
        // パラメータが数字でない場合はリダイレクト
        if (!ctype_digit($project_id . $task_id)) {
            return redirect('home')->with('flash_message', '不正な操作が行われました');
        }

        $prep = new Prep();
        $prep->task_id = $task_id;
        Auth::user()->preps()->save($prep->fill($request->all()));

        // タスクのステータスを更新
        $current_task = Auth::user()->tasks->find($task_id);
        $current_task->status = 2;
        $current_task->save();
        // Auth::user()->tasks->find($task_id)->update(['status' => '2']);

        // 一覧画面にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', '計画を登録しました。Doボタンから実行しましょう！');
    }

    // Prep編集画面を表示
    public function showEditForm($project_id, $task_id, $prep_id)
    {
        // パラメータが数字でない場合はリダイレクト
        if (!ctype_digit($project_id . $task_id . $prep_id)) {
            return redirect('home')->with('flash_message', '不正な操作が行われました');
        }
        // ログインユーザーに紐づく該当IDのレコードを取得
        $current_task = Auth::user()->tasks()->find($task_id);
        $editing_prep = $current_task->preps()->find($prep_id);
        $categories = Auth::user()->categories()->get();

        return view('preps.edit', compact('editing_prep', 'current_task', 'categories'));
    }

    // Prep編集処理
    public function edit($project_id, $task_id, $prep_id, EditPrep $request)
    {
        // パラメータが数字でない場合はリダイレクト
        if (!ctype_digit($project_id . $task_id . $prep_id)) {
            return redirect('home')->with('flash_message', '不正な操作が行われました');
        }
        // リクエストのIDからPrepデータを取得
        $editing_prep = Auth::user()->preps()->find($prep_id);

        // 該当のPrepデータをフォームの入力値で書き換えて保存
        Auth::user()->preps()->save($editing_prep->fill($request->all()));

        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', '計画を変更しました');
    }

    // Prep削除処理
    public function delete($project_id, $task_id, $prep_id)
    {
        // パラメータが数字でない場合はリダイレクト
        if (!ctype_digit($project_id . $task_id . $prep_id)) {
            return redirect('home')->with('flash_message', '不正な操作が行われました');
        }
        // リクエストで受け取ったIDのPrepを削除
        $current_task = Auth::user()->tasks()->find($task_id);
        $current_task->preps()->find($prep_id)->delete();

        // 他にPrepがない場合はタスクのステータスを更新
        if (!($current_task->preps()->count())) {
            // $current_task->update(['status' => 1]);
            $current_task->status = 1;
            $current_task->save();
        }

        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', '計画を削除しました');
    }

    // Do画面表示
    public function showDoForm($project_id, $task_id, $prep_id)
    {
        // パラメータが数字でない場合はリダイレクト
        if (!ctype_digit($project_id . $task_id . $prep_id)) {
            return redirect('home')->with('flash_message', '不正な操作が行われました');
        }

        // ログインユーザーに紐づく該当IDのPrepを取得
        $current_task = Auth::user()->tasks()->find($task_id);
        $do_prep = $current_task->preps()->find($prep_id);

        // 該当タスクのステータスが完了以外の場合は着手中に変更
        if ($current_task->status < 3) {
            $current_task->status = 3;
            $current_task->save();
        }

        // 開始日時をセッションに記録
        $temp_started_at = Carbon::now();
        session(['temp_started_at' => $temp_started_at]);

        // タスクの実行回数を取得（該当タスクに紐づく既存レビュー数）
        $done_count = $current_task->reviews()->count();

        // ログインユーザーに紐づくタスク、カテゴリーを入力フォーム用に取得
        return view('preps.do', compact('do_prep', 'current_task', 'done_count', 'temp_started_at'));
    }

    // Do完了処理
    public function done($project_id, $task_id, $prep_id)
    {
        // パラメータが数字でない場合はリダイレクト
        if (!ctype_digit($project_id . $task_id . $prep_id)) {
            return redirect('home')->with('flash_message', '不正な操作が行われました');
        }
        // 該当タスクの実行カウンタを1インクリメント
        Auth::user()->tasks()->find($task_id)->increment('done_count');

        // Review入力画面にリダイレクト
        return redirect()->route('reviews.create', ['project_id' => $project_id, 'task_id' => $task_id, 'prep_id' => $prep_id])->with('flash_message', 'ステップを1つ達成しました！振り返りを行いましょう！');
    }
}
