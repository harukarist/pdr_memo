<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;
use Illuminate\Http\Request;
use App\Http\Requests\EditTask;
use App\Http\Requests\CreateTask;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // タスク一覧表示
    public function index(int $id)
    {
        // ログインユーザーに紐づくプロジェクトを取得
        $projects = Auth::user()->projects()->get();

        // 選択されたプロジェクトIDのデータを取得
        $current_project = Project::find($id);

        // 選択されたプロジェクトに紐づくタスクを取得
        $tasks = $current_project->tasks()->get();

        // プロジェクトデータと現在のプロジェクトIDをビューテンプレートに返却
        return view('tasks.index', [
            'projects' => $projects,
            'current_project_id' => $current_project->id,
            'tasks' => $tasks
        ]);
    }

    // タスク作成画面を表示
    public function showCreateForm(int $id)
    {
        return view('tasks.create', [
            'project_id' => $id
        ]);
    }

    // タスク作成処理
    public function create(int $id, CreateTask $request)
    {
        $current_project = Project::find($id);

        $task = new Task();
        $task->task_name = $request->task_name;

        // 選択中のプロジェクトに紐づくタスクを作成
        $current_project->tasks()->save($task);

        return redirect()->route('tasks.index', [
            'project_id' => $current_project->id,
        ]);
    }
    // タスク編集画面を表示
    public function showEditForm(int $id, int $task_id)
    {
        // 該当のタスクIDのデータを取得し、ビューテンプレートに返却
        $task = Task::find($task_id);

        return view('tasks.edit', [
            'task' => $task,
        ]);
    }
    // タスク編集処理
    public function edit(int $id, int $task_id, EditTask $request)
    {
        // リクエストのIDからタスクデータを取得
        $task = Task::find($task_id);

        // 該当のタスクデータをフォームの入力値で書き換えて保存
        $task->task_name = $request->task_name;
        $task->status = $request->status;
        // $task->due_date = $request->due_date;
        $task->save();

        // 編集対象のタスクが属するプロジェクトのタスク一覧にリダイレクト
        return redirect()->route('tasks.index', [
            'project_id' => $task->project_id,
        ]);
    }
}
