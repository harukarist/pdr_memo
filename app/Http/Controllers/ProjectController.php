<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProject;

class ProjectController extends Controller
{
    // プロジェクト作成画面
    public function showCreateForm()
    {
        return view('projects/create');
    }

    // プロジェクトの作成
    // FormRequestクラスでバリデーションチェック
    public function create(CreateProject $request)
    {
        // Projectモデルのインスタンスを作成する
        $projects = new Project();
        // フォームに入力された内容を代入
        $projects->project_name = $request->project_name;
        // インスタンスの状態をデータベースに書き込む
        $projects->save();

        // そのプロジェクトのタスク一覧画面にリダイレクト
        return redirect()->route('tasks.index', [
            'project_id' => $projects->id,
        ]);
    }
}
