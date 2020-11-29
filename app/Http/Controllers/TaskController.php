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
    // タスク一覧表示
    public function index(int $project_id)
    {
        // ログインユーザーに紐づくプロジェクトを取得
        $projects = Auth::user()->projects()->get();

        // 選択されたプロジェクトに紐づくタスクを取得
        $current_project = Auth::user()->projects()->find($project_id);

        // プロジェクトが存在しない場合はホーム画面へリダイレクト
        if(empty($current_project)){
            return view('home');
        }
        $tasks = $current_project->tasks()->orderBy('updated_at', 'desc')->paginate(10);

        // $items = Auth::user()->preps()->select(DB::raw('DATE_FORMAT(preps.created_at,"%Y/%m/%d") as prepedday'), DB::raw('ROUND(SUM(preps.unit_time)/60,1) as hour'))->groupby('prepedday')->get();
        // $items = Auth::user()->tasks()->select(DB::raw('DATE_FORMAT(preps.created_at,"%Y/%m/%d") as prepedday'), DB::raw('ROUND(SUM(preps.unit_time)/60,1) as hour'))->groupby('prepedday')->get();

        // ReserveDayList::select(DB::raw('DATE_FORMAT(day, "%Y-%m") as yearmonth'), DB::raw('count(*) as count'), DB::raw('count(*) * 2000 as total'))
        //                         ->groupby('yearmonth')
        //                         ->get();

        $counter = [];
        $remained_hours = 0;
        $remained_steps = 0;

        if ($tasks->count()) {
            // ログインユーザIDを取得
            $user_id = Auth::getUser()->id;
            $tasklist = $current_project->tasks()->get();
            // 達成回数
            $records = DB::table('reviews')
                ->join('preps', 'preps.id', '=', 'reviews.prep_id')
                ->join('tasks', 'tasks.id', '=', 'preps.task_id')
                ->select('actual_time')
                ->where('preps.user_id', '=', $user_id)
                ->where('tasks.project_id', '=', $project_id)
                ->get();

            $counter = [
                'reviewed_count' => $records->count('actual_time'),
                'reviewed_hours' => round(($records->sum('actual_time')) / 60, 1),
                'completed_count' => $tasklist->where('status','=', 4)->count(),
                'doing_count' => $tasklist->where('status','=', 3)->count(),
                'prepped_count' => $tasklist->where('status','=', 2)->count(),
                'waiting_count' => $tasklist->where('status','=', 1)->count(),
            ];


            // 記録開始日
            // $first_date = Auth::user()->tasks()->orderBy('tasks.created_at', 'asc')->first()->created_at;
            // $dt = Carbon::tomorrow();
            // $df = new Carbon($first_date);
            // $days_count = $df->diffInDays($dt);

            // 今後の予定時間、予定ステップ数
            $tasks_ongoing = $tasklist->where('status', '<', 4);
            $remained_minutes = 0;
            $remained_steps = 0;
            if (isset($tasks_ongoing)) {
                foreach ($tasks_ongoing as $task) {
                    // 各Prepの残り時間、残り回数を計算
                    $done_minutes = 0;
                    $done_steps = 0;
                    // echo ($task->task_name . '<br>');
                    if (isset($task->preps)) {
                        foreach ($task->preps as $prep) {
                            // Prepの予想時間
                            $prep_minutes = ($prep->unit_time) * ($prep->estimated_steps);
                            if (isset($prep->reviews)) {
                                foreach ($prep->reviews as $review) {
                                    // Prepの実行済み時間、実行回数
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
        }

        // プロジェクトデータと現在のプロジェクトIDをビューテンプレートに返却
        return view('tasks.index', compact('projects', 'current_project','tasks', 'counter', 'remained_hours', 'remained_steps'));
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
        $task->priority = $request->priority;
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
