<?php

namespace App\Http\Controllers;

use App\Project;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProject;
use App\Http\Requests\EditProject;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // プロジェクト作成画面
    public function showCreateForm()
    {
        return view('projects.create');
    }

    // プロジェクトの作成
    // FormRequestクラスでバリデーションチェック
    public function create(CreateProject $request)
    {
        // Projectモデルのインスタンスを作成する
        $project = new Project();
        
        // $project_name = $request->project_name;
        // $project->category_id = $request->category_id;
        // Auth::user()->projects()->save($project);
        
        // $project->fill($request->all());

        // ログインユーザーに紐づけて保存
        Auth::user()->projects()->save($project->fill($request->all()));

        // そのプロジェクトのタスク一覧画面にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project->id])->with('flash_message', 'プロジェクトを作成しました');
    }

    // プロジェクト編集画面を表示
    public function showEditForm(int $project_id)
    {
        // 該当のプロジェクトIDのデータを取得し、ビューテンプレートに返却
        $edit_project = Auth::user()->projects()->find($project_id);
        // $categories = Auth::user()->categories()->get();

        // ログインユーザーに紐づくプロジェクトを取得
        if ($edit_project) {
            $projects = Auth::user()->projects()->get();

            return view('projects.edit', compact('edit_project', 'projects',));
        } else {
            return redirect()->route('home');
        }
    }

    // プロジェクト編集処理
    public function edit(int $project_id, EditProject $request)
    {
        // リクエストのIDからプロジェクトデータを取得
        $project = Auth::user()->projects()->find($project_id);

        // 該当のプロジェクトデータをフォームの入力値で書き換えて保存
        Auth::user()->projects()->save($project->fill($request->all()));

        // 編集対象のプロジェクトが属するプロジェクトのプロジェクト一覧にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', 'プロジェクトを変更しました');
    }

    // プロジェクト削除処理
    public function delete(int $project_id)
    {
        // リクエストで受け取ったIDのプロジェクトを削除
        // Project::find($project_id)->delete();
        Project::destroy($project_id);

        // 削除対象のプロジェクトが属するプロジェクトのプロジェクト一覧にリダイレクト
        return redirect()->route('home')->with('flash_message', 'プロジェクトを削除しました');
    }
}
