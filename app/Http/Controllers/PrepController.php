<?php

namespace App\Http\Controllers;

use App\Prep;
use App\Task;
use App\Record;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PrepController extends Controller
{
    // Prep登録画面を表示
    public function showCreateForm()
    {
        // ログインユーザーに紐づくプロジェクトを取得
        $tasks = Auth::user()->tasks()->get();
        $categories = Auth::user()->categories()->get();
        $unit_times = ['5', '15', '30', '45', '60'];
        $estimated_steps = [1, 2, 3, 4, 5];

        // プロジェクトデータと現在のプロジェクトIDをビューテンプレートに返却
        // return view('tasks.index', [
        //     'projects' => $projects,
        //     'current_project_id' => $current_project->id,
        //     'tasks' => $tasks
        // ]);


        return view('preps.create', compact('tasks', 'categories', 'unit_times', 'estimated_steps'));
    }

    // Prep登録処理
    public function create(Record $record, Request $request)
    {
        $params = $request->validate([
            'prep_text' => 'required|max:255',
            'unit_time' => 'required',
            'estimated_steps' => 'required',
            'category_id' => 'required',
            // 'task_id' => 'required',
        ]);

        // $record = new Record;
        // $record->user_id = Auth::user()->id;
        // $record->target_date = Carbon::now();
        // $record->task_id = $params['task_id'];
        // $task->records()->save($record);


        // $prep = new Prep;
        // Auth::user()->preps()->save($prep->fill($params->all()));
        // $prep->record_id = $record->id;
        // $prep->prep_text = $params['prep_text'];
        // $prep->unit_time = $params['unit_time'];
        // $prep->estimated_steps = $params['estimated_steps'];
        // $prep->category_id = $params['category_id'];
        // $record->preps()->save($prep);
        Prep::create($params);

        // // 選択中のプロジェクトに紐づくタスクを作成
        return redirect()->route('records.index');
    }


    // Prep編集画面を表示
    public function showEditForm(int $record_id, int $prep_id)
    {
        // 該当のタスクIDのデータを取得し、ビューテンプレートに返却
        // $task = Task::find($task_id);

        // Categoryモデルからカテゴリー名の一覧を取得してビューに渡す
        $task_names = Auth::user()->tasks()->pluck('task_name');
        return view('preps.edit', compact('task_names'));
    }

    // Prep編集処理
    public function edit(int $record_id, int $prep_id, Request $request)
    {
        // リクエストのIDからタスクデータを取得
        // $task = Task::find($task_id);

        // 該当のタスクデータをフォームの入力値で書き換えて保存
        // $task->task_name = $request->task_name;
        // $task->status = $request->status;
        // $task->due_date = $request->due_date;
        // $task->save();

        // 編集対象のタスクが属するプロジェクトのタスク一覧にリダイレクト
        return redirect()->route('records.index', [
            // 'project_id' => $task->project_id,
        ]);
    }

    // Do画面を表示
    public function showDoForm(int $prep_id)
    {
        // 該当のタスクIDのデータを取得し、ビューテンプレートに返却
        // $task = Task::find($task_id);

        return view('preps/do', [
            // 'task' => $task,
        ]);
    }
}
