<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;
use Illuminate\Http\Request;
use App\Http\Requests\CreateTask;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    // タスク一覧表示
    public function index(int $id)
    {
        // プロジェクトデータを全て取得
        $projects = Project::all();

        // 選択されたプロジェクトIDのデータを取得
        $current_project = Project::find($id);

        // 選択されたプロジェクトに紐づくタスクを取得
        $tasks = $current_project->tasks()->get();

        // プロジェクトデータと現在のプロジェクトIDをビューテンプレートに返却
        return view('tasks/index', [
            'projects' => $projects,
            'current_project_id' => $current_project->id,
            'tasks' => $tasks
        ]);
    }

    // タスク作成画面
    public function showCreateForm(int $id)
    {
        return view('tasks/create', [
            'project_id' => $id
        ]);
    }

    // タスク作成
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
}
