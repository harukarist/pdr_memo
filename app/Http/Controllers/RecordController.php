<?php

namespace App\Http\Controllers;

use App\Prep;
use App\Task;
use App\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\AddRecord;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RecordController extends Controller
{
  // 対象の日付を設定
  public function getDate(Request $request)
  {
    //Requestのinput()でクエリーのdateを受け取る
    $date = $request->input("date");

    if ($date && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])$/", $date)) {
      //dateがYYYY-MMの場合はYYYY-MM-01に変換
      $date = $date . "-01";
    } elseif (!($date && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/", $date))) {
      //dateがYYYY-MM-DDでない場合はnull
      $date = null;
    }

    //取得出来ない時は今日の日付を取得
    if (!$date) $date = Carbon::today();

    return $date;
  }

  // 記録追加画面を表示
  public function showAddForm()
  {
    $projects = Auth::user()->projects()->get();
    $categories = Auth::user()->categories()->get();

    $dt = Carbon::now();
    // 開始時刻を日付と時刻に分ける
    $started_date = $dt->toDateString();
    $started_time = $dt->format('H:i');

    return view('records.add', compact('projects', 'categories', 'started_date', 'started_time'));
  }

  // 記録追加処理
  public function add(AddRecord $request)
  {
    // 選択されたプロジェクトに紐づくタスクを作成
    $project = Auth::user()->projects()->find($request->project_id);
    $task = new Task();
    $task->task_name = $request->task_name;
    $project->tasks()->save($task);

    // 作成したタスクに紐づくPrepを作成
    $prep = new Prep();
    $prep->task_id = $task->id;
    $prep->prep_text = $request->prep_text;
    $prep->unit_time = $request->unit_time;
    $prep->estimated_steps = $request->estimated_steps;
    Auth::user()->preps()->save($prep);

    // タスクのステータスを更新
    Auth::user()->tasks()->find($task->id)->update(['status' => 2]);

    // Prepに紐づくReviewを作成
    $review = new Review();
    // 入力された日付、時刻からdateTimeを生成
    $review->started_at = Carbon::createFromFormat(
      'Y-m-d H:i',
      $request->started_date . ' ' . $request->started_time
    );
    $review->user_id = Auth::id();

    // その他の項目をfillで登録
    $prep->reviews()->save($review->fill($request->all()));

    // 完了済みチェックがonの場合はタスクのステータスを4（完了）に切り替え
    if ($request->task_completed) {
      $task->update(['status' => 4]);
    } else {
      $task->update(['status' => 3]);
    }
    // 一覧画面にリダイレクト
    return redirect()->route('reports.weekly')->with('flash_message', '記録を追加しました');
  }
}
