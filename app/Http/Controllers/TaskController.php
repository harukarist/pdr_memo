<?php

namespace App\Http\Controllers;

use App\Task;
use App\Review;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\EditTask;
use App\Http\Requests\CreateTask;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // --------------------------------------------------------
    // API
    // タスク編集処理
    public function done(int $task_id, Request $request)
    {
        // リクエストのIDからタスクデータを取得
        // $task = Task::find($task_id);
        $task = Auth::user()->tasks()->find($task_id);

        // 該当のタスクデータをフォームの入力値で書き換えて保存
        $task->status = 4;
        $task->save();
    }
    // タスク編集処理
    public function undone(int $task_id, Request $request)
    {
        // リクエストのIDからタスクデータを取得
        // $task = Task::find($task_id);
        $task = Auth::user()->tasks()->find($task_id);

        // 該当のタスクデータをフォームの入力値で書き換えて保存
        $task->status = 3;
        $task->save();
    }
    // タスク削除処理
    public function deleteApi(int $task_id, Request $request)
    {
        Task::destroy($task_id);
    }

    // public function list()
    // {
    //     $user_id = Auth::id();
    //     $tasks = Project::with('tasks.preps.reviews')->where('projects.user_id', $user_id)->get();
    //     return response()->json(
    //         [
    //             'tasks' => $tasks,
    //         ],
    //         200,
    //         [],
    //         JSON_UNESCAPED_UNICODE
    //     );
    // }


    // --------------------------------------------------------
    // タスク一覧表示
    public function index(int $project_id, Request $request)
    {
        // ログインユーザーに紐づくプロジェクトを取得
        $projects = Auth::user()->projects()->get();
        // 選択中のプロジェクトを取得
        $current_project = $projects->find($project_id);

        // Eager Loading
        // クエリビルダを使う場合は動的プロパティでなくリレーションメソッドが必要
        // $user_id = Auth::id();
        // $current_project = Project::with('tasks.preps.reviews')
        //     ->where('projects.user_id', $user_id)
        //     ->where('projects.id', $project_id)
        //     ->get();

        // プロジェクトが存在しない場合はホーム画面へリダイレクト
        if (empty($current_project)) {
            return view('home');
        }

        if ($request->is('projects/*/tasks/done')) {
            // 完了リストページからのリクエストの場合は完了タスクを表示
            $tasks = $current_project->tasks()->where('status', '=', '4')
                ->orderBy('updated_at', 'desc')->paginate(15);
            $has_done = true;
        } else {
            // 未完了リストページからのリクエストの場合は未完了タスクを表示
            $tasks = $current_project->tasks()->where('status', '<', '4')
                ->orderBy('priority', 'desc')->orderBy('due_date', 'desc')->orderBy('updated_at', 'desc')
                ->paginate(15);
            $has_done = false;
        }


        // 開始からの記録を集計
        $counter = $this->summary($current_project, $project_id);
        // dd($counter);
        return view('tasks.index', compact('projects', 'current_project', 'tasks', 'counter', 'has_done'));
    }

    // タスク作成処理
    public function create(int $project_id, CreateTask $request)
    {
        // 選択されたプロジェクトIDのデータを取得
        $current_project = Auth::user()->projects()->find($project_id);

        $task = new Task();
        $task->task_name = $request->task_name;
        $task->priority = $request->priority;
        $task->due_date = $request->due_date;
        $task->project_id = $project_id;
        $task->save();

        // 選択中のプロジェクトに紐づくタスクを作成
        $current_project->tasks()->save($task);

        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('content_flash_message', 'タスクを作成しました');
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
        // $task->task_name = $request->task_name;
        // $task->status = $request->status;
        // $task->priority = $request->priority;
        // $task->due_date = $request->due_date;

        $task->fill($request->all());
        $task->save();

        // 編集対象のタスクが属するプロジェクトのタスク一覧にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('content_flash_message', 'タスクを変更しました');
    }

    // タスク削除処理
    public function delete(int $project_id, int $task_id)
    {
        // リクエストで受け取ったIDのタスクをソフトデリート
        // Task::find($task_id)->delete();
        Task::destroy($task_id);

        // 削除対象のタスクが属するプロジェクトのタスク一覧にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('content_flash_message', 'タスクを削除しました');
    }


    // サマリー表示
    public function summary($current_project)
    {
        $counter = [];
        // ログインユーザIDを取得
        $user_id = Auth::id();
        // プロジェクトに紐づくタスクを取得
        $project_tasks = $current_project->tasks()->orderBy('tasks.created_at', 'asc')->get();

        // 該当プロジェクトに紐づく達成時間を取得
        $records = DB::table('reviews')
            ->join('preps', 'preps.id', '=', 'reviews.prep_id')
            ->join('tasks', 'tasks.id', '=', 'preps.task_id')
            ->select('actual_time', 'started_at')
            ->where('preps.user_id', '=', $user_id)
            ->where('reviews.deleted_at', null)
            ->where('tasks.project_id', '=', $current_project->id)
            ->get();

        // 達成時間の合計と回数、タスク件数を取得
        $counter = [
            'reviewed_count' => $records->count('actual_time'),
            'reviewed_hour' => round(($records->sum('actual_time')) / 60, 1),
            'completed_count' => $project_tasks->where('status', '=', 4)->count(),
            'doing_count' => $project_tasks->where('status', '=', 3)->count(),
            'prepped_count' => $project_tasks->where('status', '=', 2)->count(),
            'waiting_count' => $project_tasks->where('status', '=', 1)->count(),
        ];


        // 達成開始日
        if (isset($records[0])) {
            $started_at = $records->first()->started_at;
            $dt = Carbon::tomorrow();
            $df = new Carbon($started_at);
            $counter['days_count'] = $df->diffInDays($dt);
            $counter['started_at'] = Carbon::parse($started_at)->format("Y/m/d(D)");
        } else {
            $counter['days_count'] = 0;
        }
        // dd($started_at);

        // 今後の予定時間、予定ステップ数
        $tasks_ongoing = $project_tasks->where('status', '<', 4);
        $done_minutes = 0;
        $done_steps = 0;
        $prep_minutes = 0;
        $prep_steps = 0;
        if (isset($tasks_ongoing[0])) {
            foreach ($tasks_ongoing as $task) {
                // 各Prepの残り時間、残り回数を計算
                if (isset($task->preps)) {
                    foreach ($task->preps as $prep) {
                        // Prepの予想時間
                        $prep_minutes = ($prep->unit_time * $prep->estimated_steps) + $prep_minutes;
                        $prep_steps = $prep->estimated_steps + $prep_steps;
                        if (isset($prep->reviews)) {
                            foreach ($prep->reviews as $review) {
                                // Prepの実行済み時間、実行回数
                                $done_minutes = $done_minutes + $review->actual_time;
                                $done_steps = $done_steps + 1;
                                // echo ('実行回数' . $done_steps . ' ');
                                // echo ('実行時間' . $done_minutes . '<br>');
                            }
                        }
                    }
                }
            }
            // dd($remained_minutes, $prep_minutes, $done_minutes);
        }
        $remained_minutes = $prep_minutes - $done_minutes;
        $remained_steps = $prep_steps - $done_steps;
        if ($remained_minutes) {
            $counter['remained_hour'] = round(($remained_minutes) / 60, 1);
            $counter['remained_steps'] = $remained_steps;
        } else {
            $counter['remained_hour'] = 0;
            $counter['remained_steps'] = 0;
        }
        return $counter;
    }
}
