<?php

namespace App\Http\Controllers;

use App\Prep;
use App\Task;
use App\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\AddRecord;
use App\Http\Requests\EditRecord;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RecordController extends Controller
{
  // 対象の日付を設定
  public function getCarbon($date)
  {

    //dateがYYYY-MM-DDの場合は対象日付のCarbonを生成
    if ($date && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/", $date)) {
      $date = new Carbon($date);
    } elseif ($date && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])$/", $date)) {
      //dateがYYYY-MMの場合はYYYY-MM-01に変換
      $date = $date . "-01";
      $date = new Carbon($date);
    } else {
      //dateがYYYY-MM-DDでない場合はnull
      $date = null;
    }

    //取得出来ない時は今日の日付を取得
    if (!$date) $date = Carbon::today();

    return $date;
  }

  // 記録追加画面を表示
  public function showAddForm(Request $request)
  {
    $projects = Auth::user()->projects()->get();
    $categories = Auth::user()->categories()->get();

    // 日付のクエリパラメータがあれば取得
    $date = $request->query('date');
    $target_date = $this->getCarbon($date);

    // 現在時刻の取得用Carbon
    $target_time = new Carbon();

    if ($target_date) {
      // 開始時刻を日付と時刻に分ける
      $started_date = $target_date->toDateString();
      $started_time = $target_time->format('H:i');
    } else {
      $started_date = '';
      $started_date = '';
    }

    return view('records.add', compact('projects', 'categories', 'started_date', 'started_time'));
  }

  // 記録追加処理
  public function add(AddRecord $request)
  {
    $url = $request->url;

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
    // フォーム遷移前のページにリダイレクト
    return redirect($url)->with('flash_message', '記録を追加しました');
  }


  // 記録編集画面
  public function showEditForm($project_id, $task_id, $prep_id, $review_id)
  {
    // パラメータが数字でない場合はリダイレクト
    if (!ctype_digit($project_id . $task_id . $prep_id . $review_id)) {
      return redirect('home')->with('flash_message', '不正な操作が行われました');
    }
    // ログインユーザーに紐づく該当IDのreviewを取得
    $editing_task = Auth::user()->tasks()->find($task_id);
    $editing_prep = $editing_task->preps()->find($prep_id);
    $editing_review = $editing_prep->reviews()->find($review_id);
    $categories = Auth::user()->categories()->get();
    $projects = Auth::user()->projects()->get();

    // フォーム表示用に開始日時を日付と時刻に分ける
    if (!empty($editing_review->started_at)) {
      $sa = new Carbon($editing_review->started_at);
    } else {
      // 記録がない場合は現在時刻から予定単位時間を引いた時間
      $sa = Carbon::now()->subMinutes($editing_review->prep->unit_time);
    }
    $started_date = $sa->toDateString();
    $started_time = $sa->format('H:i');

    return view('records.edit', compact('editing_prep', 'editing_review', 'editing_task', 'categories', 'projects', 'started_date', 'started_time'));
  }

  // 編集処理
  public function edit($project_id, $task_id, $prep_id, $review_id, EditRecord $request)
  {
    // パラメータが数字でない場合はリダイレクト
    if (!ctype_digit($project_id . $task_id . $prep_id . $review_id)) {
      return redirect('home')->with('flash_message', '不正な操作が行われました');
    }
    $url = $request->url;

    // リクエストのIDからデータを取得
    $editing_task = Auth::user()->tasks()->find($task_id);
    if ($project_id <> $request->project_id) {
      $editing_task->project_id = $request->project_id;
    }
    // 完了済みチェックがonの場合はタスクのステータスを4（完了）に切り替え
    if ($request->task_completed) {
      $editing_task->status = 4;
    }
    // 該当のtaskデータをフォームの入力値で書き換えて保存
    $editing_task->fill($request->all());
    $editing_task->save();

    // 該当のprepデータをフォームの入力値で書き換えて保存
    $editing_prep = $editing_task->preps()->find($prep_id);
    $editing_prep->fill($request->all());
    $editing_prep->save();

    $editing_review = $editing_prep->reviews()->find($review_id);

    // 入力された日付、時刻からdateTimeを生成
    $editing_review->started_at = Carbon::createFromFormat(
      'Y-m-d H:i',
      $request->started_date . ' ' . $request->started_time
    );
    // 実際にかかった時間を足して終了日時のdateTimeを生成
    $dt = new Carbon($editing_review->started_at);
    $editing_review->finished_at = $dt->addMinutes($request->actual_time);

    // 該当のreviewデータをフォームの入力値で書き換えて保存
    $editing_review->fill($request->all());
    $editing_review->save();

    // フォーム遷移前のページにリダイレクト
    return redirect($url)->with('flash_message', '記録を変更しました');
  }

  // review削除処理
  public function delete(Request $request, $project_id, $task_id, $prep_id, $review_id)
  {
    // パラメータが数字でない場合はリダイレクト
    if (!ctype_digit($project_id . $task_id . $prep_id . $review_id)) {
      return redirect('home')->with('flash_message', '不正な操作が行われました');
    }
    $url = $request->url;

    // リクエストで受け取ったIDのreviewを削除
    Auth::user()->preps()->find($prep_id)->reviews()->find($review_id)->delete();
    // review::find($review_id)->delete();
    // review::destroy($review_id);

    // フォーム遷移前のページにリダイレクト
    return redirect($url)->with('flash_message', '記録を削除しました');
  }
}
