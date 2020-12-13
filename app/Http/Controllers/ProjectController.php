<?php

namespace App\Http\Controllers;

use App\Project;
use App\Category;
use App\Http\Requests\EditProject;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProject;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CategoryController;

class ProjectController extends Controller
{
    // プロジェクト作成画面
    public function showCreateForm()
    {
        // ログインユーザーに紐づくカテゴリーを取得
        $categories = Auth::user()->categories()->get();

        if (empty($categories->count())) {
            CategoryController::createUsersCategory('Input');
            CategoryController::createUsersCategory('Output');
            CategoryController::createUsersCategory('Etc');
            $categories = Auth::user()->categories()->get();
        }
        return view('projects.create', compact('categories'));
    }

    // プロジェクトの作成
    // FormRequestクラスでバリデーションチェック
    public function post(CreateProject $request)
    {
        // Projectモデルのインスタンスを作成する
        $project = new Project();

        // ログインユーザーに紐づけて保存
        Auth::user()->projects()->save($project->fill($request->all()));

        // そのプロジェクトのタスク一覧画面にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project->id])->with('flash_message', 'プロジェクトを作成しました');
    }

    // プロジェクト編集画面を表示
    public function showEditForm($project_id)
    {
        // パラメータが数字でない場合はリダイレクト
        if (!ctype_digit($project_id)) {
            return redirect('home')->with('flash_message', '不正な操作が行われました');
        }
        // 該当のプロジェクトIDのデータを取得し、ビューテンプレートに返却
        $edit_project = Auth::user()->projects()->find($project_id);
        // ログインユーザーに紐づくカテゴリーを取得
        $categories = Auth::user()->categories()->get();

        if ($edit_project) {
            return view('projects.edit', compact('edit_project', 'categories'));
        } else {
            return redirect()->route('home');
        }
    }

    // プロジェクト編集処理
    public function edit($project_id, EditProject $request)
    {
        // パラメータが数字でない場合はリダイレクト
        if (!ctype_digit($project_id)) {
            return redirect('home')->with('flash_message', '不正な操作が行われました');
        }
        // リクエストのIDからプロジェクトデータを取得
        $project = Auth::user()->projects()->find($project_id);

        // 該当のプロジェクトデータをフォームの入力値で書き換えて保存
        Auth::user()->projects()->save($project->fill($request->all()));

        // 編集対象のプロジェクトが属するプロジェクトのプロジェクト一覧にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', 'プロジェクトを変更しました');
    }

    // プロジェクト削除処理
    public function delete($project_id)
    {
        // パラメータが数字でない場合はリダイレクト
        if (!ctype_digit($project_id)) {
            return redirect('home')->with('flash_message', '不正な操作が行われました');
        }
        // リクエストで受け取ったIDのプロジェクトを削除
        // Project::find($project_id)->delete();
        // Project::destroy($project_id);
        $deleting_project = Auth::user()->projects->find($project_id);
        $deleting_project->tasks()->delete();
        $deleting_project->delete();


        // 削除対象のプロジェクトが属するプロジェクトのプロジェクト一覧にリダイレクト
        return redirect()->route('home')->with('flash_message', 'プロジェクトを削除しました');
    }
}
