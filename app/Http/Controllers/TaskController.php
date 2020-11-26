<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\EditTask;
use App\Http\Requests\CreateTask;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // タスク一覧表示
    public function index(int $project_id)
    {
        // ログインユーザーに紐づくプロジェクトを取得
        $projects = Auth::user()->projects()->get();

        // 選択されたプロジェクトに紐づくタスクを取得
        $tasks = Auth::user()->projects()->find($project_id)->tasks()->orderBy('updated_at', 'desc')->paginate(10);
        $current_project_id = $project_id;

        // 達成時間（1時間単位に四捨五入して小数点第一位まで表示）
        $reviewed_hours = Auth::user()->reviews()->sum('actual_time');
        if ($reviewed_hours) {
            $reviewed_hours = round(($reviewed_hours) / 60, 1);
        }
        // 達成回数
        $reviewed_count = Auth::user()->reviews()->count('actual_time');
        // タスク数
        $completed_count = Auth::user()->tasks()->where('status', 3)->count();
        $doing_count = Auth::user()->tasks()->where('status', 2)->count();

        $waiting_count = Auth::user()->tasks()->join('preps', 'tasks.id', '=', 'preps.task_id')->where('tasks.status', 1)->distinct()->count();
        // $waiting_count = Auth::user()->tasks()->join('preps', function ($join) {
        //     $join->on('tasks.id', '=', 'preps.task_id')->where('tasks.status', 1);
        // })->distinct()->count();

        $nopreps_count = Auth::user()->tasks()->leftJoin('preps','tasks.id', '=', 'preps.task_id')->whereNull('task_id')->count();

        // 記録開始日
        $first_date = Auth::user()->reviews()->orderBy('created_at', 'asc')->first()->created_at;
        $dt = Carbon::tomorrow();
        $df = new Carbon($first_date);
        $days_count = $df->diffInDays($dt);


        // 今後の予定時間、予定ステップ数
        $tasks_ongoing = Auth::user()->tasks()->where('status', '<', 3)->get();
        $remained_minutes = 0;
        $remained_steps = 0;
        if (isset($tasks_ongoing)) {
            foreach ($tasks_ongoing as $task) {
                $done_minutes = 0;
                $done_steps = 0;
                // echo ($task->task_name . '<br>');
                if (isset($task->preps)) {
                    foreach ($task->preps as $prep) {
                        // echo ('予想回数' . $prep->estimated_steps . ' ');
                        $prep_minutes = ($prep->unit_time) * ($prep->estimated_steps);
                        // echo ('予想時間' . $prep_minutes . '<br>');
                        if (isset($prep->reviews)) {
                            foreach ($prep->reviews as $review) {
                                $done_minutes = $done_minutes + $review->actual_time;
                                $done_steps = $done_steps + 1;
                                // echo ('実行回数' . $done_steps . ' ');
                                // echo ('実行時間' . $done_minutes . '<br>');
                            }
                        }

                        $remained_minutes = $remained_minutes + ($prep_minutes - $done_minutes);
                        $remained_steps = $remained_steps + ($prep->estimated_steps - $done_steps);
                    }
                }
            }
        }
        if ($remained_minutes) {
            $remained_hours = round(($remained_minutes) / 60, 1);
        }

        // プロジェクトデータと現在のプロジェクトIDをビューテンプレートに返却
        return view('tasks.index', compact('projects', 'tasks', 'current_project_id', 'reviewed_hours', 'reviewed_count', 'remained_hours', 'remained_steps', 'completed_count', 'doing_count', 'waiting_count', 'nopreps_count','days_count', 'first_date'));
    }

    // タスク作成画面を表示
    public function showCreateForm(int $project_id)
    {
        return view('tasks.create', [
            'project_id' => $project_id
        ]);
    }

    // タスク作成処理
    public function taskAdd(int $project_id, CreateTask $request)
    {
        // 選択されたプロジェクトIDのデータを取得
        $current_project = Auth::user()->projects()->find($project_id);

        $task = new Task();
        $task->task_name = $request->task_name;

        // 選択中のプロジェクトに紐づくタスクを作成
        $current_project->tasks()->save($task);

        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', 'タスクを作成しました');
    }

    // タスク作成処理
    public function create(int $project_id, CreateTask $request)
    {
        // 選択されたプロジェクトIDのデータを取得
        $current_project = Auth::user()->projects()->find($project_id);

        $task = new Task();
        $task->task_name = $request->task_name;

        // 選択中のプロジェクトに紐づくタスクを作成
        $current_project->tasks()->save($task);

        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', 'タスクを作成しました');
    }

    // タスク編集画面を表示
    public function showEditForm(int $project_id, int $task_id)
    {
        // 該当のタスクIDのデータを取得し、ビューテンプレートに返却
        // $task = Task::find($task_id);
        $editing_task = Auth::user()->tasks()->find($task_id);

        return view('tasks.edit', compact('editing_task'));
    }

    // タスク編集処理
    public function edit(int $project_id, int $task_id, EditTask $request)
    {
        // リクエストのIDからタスクデータを取得
        // $task = Task::find($task_id);
        $task = Auth::user()->tasks()->find($task_id);

        // 該当のタスクデータをフォームの入力値で書き換えて保存
        $task->task_name = $request->task_name;
        $task->status = $request->status;
        // $task->due_date = $request->due_date;
        $task->save();

        // 編集対象のタスクが属するプロジェクトのタスク一覧にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', 'タスクを変更しました');
    }

    // タスク削除処理
    public function delete(int $project_id, int $task_id)
    {
        // リクエストで受け取ったIDのタスクをソフトデリート
        // Task::find($task_id)->delete();
        Task::destroy($task_id);

        // 削除対象のタスクが属するプロジェクトのタスク一覧にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', 'タスクを削除しました');
    }
}
