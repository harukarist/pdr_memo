<?php

namespace App\Http\Controllers;

use App\Prep;
use App\Task;
use App\Project;
use App\Category;
use App\Http\Requests\EditPrep;
use App\Http\Requests\CreatePrep;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PrepController extends Controller
{
    // Prep登録画面を表示
    public function showCreateForm()
    {
        // ログインユーザーに紐づくタスク、カテゴリーを取得
        $tasks = Auth::user()->tasks()->get();
        $categories = Auth::user()->categories()->get();
        $unit_times = ['5', '15', '30', '45', '60'];
        $estimated_steps = [1, 2, 3, 4, 5];

        return view('preps.create', compact('tasks', 'categories', 'unit_times', 'estimated_steps'));
    }

    // Prep登録処理
    public function create(CreatePrep $request)
    {
        $prep = new Prep();
        $prep->prep_text = $request->prep_text;
        $prep->unit_time = $request->unit_time;
        $prep->estimated_steps = $request->estimated_steps;
        $prep->category_id = $request->category_id;
        $prep->task_id = $request->task_id;
        Auth::user()->preps()->save($prep);
        // Auth::user()->preps()->save($prep->fill($request->all()));

        // // 選択中のプロジェクトに紐づくタスクを作成
        return redirect()->route('records.index');
    }

    // Prep編集画面を表示
    public function showEditForm(int $id)
    {
        // ログインユーザーに紐づく該当IDのPrepを取得
        // $editing_prep = Prep::find($id);
        $editing_prep = Auth::user()->preps()->find($id);

        // ログインユーザーに紐づくタスク、カテゴリーを入力フォーム用に取得
        $tasks = Auth::user()->tasks()->get();
        $categories = Auth::user()->categories()->get();
        $unit_times = ['5', '15', '30', '45', '60'];
        $estimated_steps = [1, 2, 3, 4, 5];

        return view('preps.edit', compact('editing_prep', 'tasks', 'categories', 'unit_times', 'estimated_steps'));
    }

    // Prep編集処理
    public function edit(int $id, EditPrep $request)
    {
        // リクエストのIDからPrepデータを取得
        $editing_prep = Auth::user()->preps()->find($id);

        // 該当のPrepデータをフォームの入力値で書き換えて保存
        // $editing_prep->prep_text = $request->prep_text;
        // $editing_prep->save();
        Auth::user()->preps()->save($editing_prep->fill($request->all()));

        // 編集対象のPrepが属するPrepのPrep一覧にリダイレクト
        return redirect()->route('records.index')->with('flash_message', 'Prepを変更しました');
    }

    // Prep削除処理
    public function delete(int $prep_id)
    {
        // リクエストで受け取ったIDのPrepを削除
        // Auth::user()->preps()->delete($id);
        // Prep::find($id)->delete();
        Prep::destroy($prep_id);

        // 削除対象のPrepが属するPrepのPrep一覧にリダイレクト
        return redirect()->route('records.index')->with('flash_message', 'Prepを削除しました');
    }
}
