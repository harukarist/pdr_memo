<?php

namespace App\Http\Controllers;

use App\Prep;
use App\Task;
use App\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\AddReview;
use App\Http\Requests\EditReview;
use App\Http\Requests\CreateReview;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Review登録画面を表示
    public function showCreateForm($project_id, $task_id, $prep_id, Request $request)
    {
        // パラメータが数字でない場合はリダイレクト
        if (!ctype_digit($project_id . $task_id . $prep_id)) {
            return redirect('home')->with('flash_message', '不正な操作が行われました');
        }
        // ログインユーザーに紐づく該当IDのレコードを取得
        $current_task = Auth::user()->tasks()->find($task_id);
        $done_prep = $current_task->preps()->find($prep_id);
        $categories = Auth::user()->categories()->get();

        // 現在時刻を取得
        $dt = Carbon::now();
        if ($request->session()->has('temp_started_at')) {
            // セッションに記録した開始時刻の値を取得して破棄
            $sa = $request->session()->pull('temp_started_at');
        } else {
            // セッションがない場合は現在時刻から予定単位時間を引いた時間を開始時間とする
            $sa = $dt->copy()->subMinutes($done_prep->unit_time);
        }
        // 開始時刻を日付と時刻に分ける
        $started_date = $sa->toDateString();
        $started_time = $sa->format('H:i');
        // dd($started_date, $started_time);

        // 現在時刻と開始時刻の差を実際にかかった時間とする
        $actual_time = $sa->diffInMinutes($dt);
        if (empty($actual_time)) {
            $actual_time = $done_prep->unit_time;
        }

        // タスク実行回数を取得
        $done_count = $current_task->done_count;

        return view('reviews.create', compact('done_prep', 'current_task', 'categories', 'started_date', 'started_time', 'done_count', 'actual_time'));
    }

    // review登録処理
    public function create($project_id, $task_id, $prep_id, CreateReview $request)
    {
        // パラメータが数字でない場合はリダイレクト
        if (!ctype_digit($project_id . $task_id . $prep_id)) {
            return redirect('home')->with('flash_message', '不正な操作が行われました');
        }
        // リクエストのIDからprepデータを取得
        $current_prep = Auth::user()->preps()->find($prep_id);

        $review = new Review();
        // 該当のPrepIDを登録
        $review->prep_id = $prep_id;
        $review->user_id = Auth::id();

        // 入力された日付、時刻から開始日時のdateTimeを生成
        $review->started_at = Carbon::createFromFormat(
            'Y-m-d H:i',
            $request->started_date . ' ' . $request->started_time
        );
        // 実際にかかった時間を足して終了日時のdateTimeを生成
        $dt = new Carbon($review->started_at);
        $review->finished_at = $dt->addMinutes($request->actual_time);

        // その他の項目をfillで登録
        $current_prep->reviews()->save($review->fill($request->all()));

        // 完了済みチェックがonの場合はタスクのステータスを4（完了）に切り替え
        if ($request->task_completed) {
            Auth::user()->projects()->find($project_id)->tasks()->where('id', $task_id)->update(['status' => 4]);
        }
        // 一覧画面にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', '振り返りを登録しました。少し休憩して、次のタスクに移りましょう！');
    }

    // review編集画面を表示
    public function showEditForm($project_id, $task_id, $prep_id, $review_id)
    {
        // パラメータが数字でない場合はリダイレクト
        if (!ctype_digit($project_id . $task_id . $prep_id . $review_id)) {
            return redirect('home')->with('flash_message', '不正な操作が行われました');
        }
        // ログインユーザーに紐づく該当IDのreviewを取得
        $editing_review = Auth::user()->preps()->find($prep_id)->reviews()->find($review_id);
        $current_task = Auth::user()->tasks()->find($task_id);
        $categories = Auth::user()->categories()->get();

        // フォーム表示用に開始日時を日付と時刻に分ける
        if (!empty($editing_review->started_at)) {
            $sa = new Carbon($editing_review->started_at);
        } else {
            // 記録がない場合は現在時刻から予定単位時間を引いた時間
            $sa = Carbon::now()->subMinutes($editing_review->prep->unit_time);
        }
        $started_date = $sa->toDateString();
        $started_time = $sa->format('H:i');

        return view('reviews.edit', compact('editing_review', 'current_task', 'categories', 'started_date', 'started_time'));
    }

    // review編集処理
    public function edit($project_id, $task_id, $prep_id, $review_id, EditReview $request)
    {
        // パラメータが数字でない場合はリダイレクト
        if (!ctype_digit($project_id . $task_id . $prep_id . $review_id)) {
            return redirect('home')->with('flash_message', '不正な操作が行われました');
        }
        // リクエストのIDからreviewデータを取得
        $editing_review = Auth::user()->preps()->find($prep_id)->reviews()->find($review_id);
        // $editing_review = Auth::user()->reviews()->find($review_id);

        // 入力された日付、時刻からdateTimeを生成
        $editing_review->started_at = Carbon::createFromFormat(
            'Y-m-d H:i',
            $request->started_date . ' ' . $request->started_time
        );
        // 実際にかかった時間を足して終了日時のdateTimeを生成
        $dt = new Carbon($editing_review->started_at);
        $editing_review->finished_at = $dt->addMinutes($request->actual_time);

        // 完了済みチェックがonの場合はタスクのステータスを4（完了）に切り替え
        if ($request->task_completed) {
            Auth::user()->projects()->find($project_id)->tasks()->where('id', $task_id)->update(['status' => 4]);
        }

        // 該当のreviewデータをフォームの入力値で書き換えて保存
        $editing_review->fill($request->all());
        $editing_review->save();

        // 編集対象のreviewが属するreviewのreview一覧にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', '振り返りを変更しました');
    }

    // review削除処理
    public function delete($project_id, $task_id, $prep_id, $review_id)
    {
        // パラメータが数字でない場合はリダイレクト
        if (!ctype_digit($project_id . $task_id . $prep_id . $review_id)) {
            return redirect('home')->with('flash_message', '不正な操作が行われました');
        }
        // リクエストで受け取ったIDのreviewを削除
        Auth::user()->preps()->find($prep_id)->reviews()->find($review_id)->delete();
        // review::find($review_id)->delete();
        // review::destroy($review_id);

        // 削除対象のreviewが属するreviewのreview一覧にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', '振り返りを削除しました');
    }
}
