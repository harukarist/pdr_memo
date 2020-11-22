<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProject;
use App\Http\Requests\EditProject;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // プロジェクト作成画面
    public function showCreateForm()
    {
        // ログインユーザーに紐づくプロジェクトを取得
        $projects = Auth::user()->projects()->get();

        return view('projects.create', compact('projects'));
    }

    // プロジェクトの作成
    // FormRequestクラスでバリデーションチェック
    public function create(CreateProject $request)
    {
        // Projectモデルのインスタンスを作成する
        $project = new Project();
        // フォームに入力された内容を代入
        $project->project_name = $request->project_name;
        // ログインユーザーに紐づけて保存
        Auth::user()->project()->save($projects);

        // そのプロジェクトのタスク一覧画面にリダイレクト
        return redirect()->route('projects.create')->with('flash_message', 'プロジェクトを作成しました');
    }

    // プロジェクト編集画面を表示
    public function showEditForm(int $id)
    {
        // 該当のプロジェクトIDのデータを取得し、ビューテンプレートに返却
        $edit_project = Project::find($id);

        // ログインユーザーに紐づくプロジェクトを取得
        $projects = Auth::user()->projects()->get();

        return view('projects.create', compact('edit_project', 'projects'));
    }

    // プロジェクト編集処理
    public function edit(int $id, EditProject $request)
    {
        // リクエストのIDからプロジェクトデータを取得
        $project = Project::find($id);

        // 該当のプロジェクトデータをフォームの入力値で書き換えて保存
        $project->project_name = $request->project_name;
        $project->save();

        // 編集対象のプロジェクトが属するプロジェクトのプロジェクト一覧にリダイレクト
        return redirect()->route('projects.create')->with('flash_message', 'プロジェクトを変更しました');
    }

    // プロジェクト削除処理
    public function delete(int $id)
    {
        // リクエストで受け取ったIDのプロジェクトを削除
        // Project::find($id)->delete();
        Project::destroy($id);

        // 削除対象のプロジェクトが属するプロジェクトのプロジェクト一覧にリダイレクト
        return redirect()->route('projects.create')->with('flash_message', 'プロジェクトを削除しました');
    }
}
