<?php

namespace App\Http\Controllers\Api;

use App\Task;
use App\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TaskListController extends Controller
{
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
    // 優先度の変更
    public function changePriority(int $task_id, int $priority_level)
    {
        $task = Auth::user()->tasks()->find($task_id);

        $task->priority = $priority_level;
        $task->save();
    }
    // 更新
    public function edit($task_id, Request $request)
    {
        $task = Auth::user()->tasks()->find($task_id);

        // $task->due_date = $request->due_date;
        $task->update($request->all());
        $task->save();
    }
    // 更新
    public function changeDueDate($task_id, Request $request)
    {
        $task = Auth::user()->tasks()->find($task_id);

        $task->due_date = $request->due_date;
        $task->save();
    }
    // タスク削除処理
    public function delete(int $task_id)
    {
        $deleting_task = Auth::user()->tasks->find($task_id);
        $deleting_task->reviews()->delete();
        $deleting_task->preps()->delete();
        $deleting_task->delete();
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
}
