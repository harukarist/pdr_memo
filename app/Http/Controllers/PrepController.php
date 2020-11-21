<?php

namespace App\Http\Controllers;

use App\Prep;
use App\Category;
use App\Project;
use App\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePrep;
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
        Auth::user()->preps()->save($prep);
        // Auth::user()->preps()->save($prep->fill($request->all()));

        // // 選択中のプロジェクトに紐づくタスクを作成
        return redirect()->route('records.index');
    }
}
