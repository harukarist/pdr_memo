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
    public function showCreateForm(int $project_id, int $task_id, int $prep_id, Request $request)
    {
        $dt = Carbon::now();
        // ログインユーザーに紐づく該当IDのレコードを取得
        $current_task = Auth::user()->tasks()->find($task_id);
        $done_prep = $current_task->preps()->find($prep_id);

        if ($request->session()->has('started_at')) {
            // セッションに記録した開始時刻の値を取得して破棄
            $sa = $request->session()->pull('started_at');
        } else {
            // セッションがない場合は現在時刻から予定単位時間を引いた時間を開始時間とする
            $sa = $dt->copy()->subMinutes($done_prep->unit_time);
        }
        // 開始時刻を日付と時刻に分ける
        $started_date = $sa->toDateString();
        $started_time = $sa->format('H:i');

        // 現在時刻と開始時刻の差を実際にかかった時間とする
        $actual_time = $sa->diffInMinutes($dt);

        // タスク実行回数を取得
        $done_count = $current_task->done_count;

        return view('reviews.create', compact('done_prep', 'current_task',  'started_date', 'started_time', 'done_count', 'actual_time'));
    }

    // review登録処理
    public function create(int $project_id, int $task_id, int $prep_id, CreateReview $request)
    {
        // リクエストのIDからprepデータを取得
        $current_prep = Auth::user()->preps()->find($prep_id);

        $review = new Review();
        // 該当のPrepIDを登録
        $review->prep_id = $prep_id;

        // 入力された日付、時刻からdateTimeを生成
        $review->started_at = Carbon::createFromFormat(
            'Y-m-d H:i',
            $request->started_date . ' ' . $request->started_time
        );

        // その他の項目をfillで登録
        $current_prep->reviews()->save($review->fill($request->all()));

        // 完了済みチェックがonの場合はタスクのステータスを4（完了）に切り替え
        if ($request->task_completed) {
            Auth::user()->projects()->find($project_id)->tasks()->where('id', $task_id)->update(['status' => 4]);
        }
        // 一覧画面にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', 'Reviewを登録しました');
    }

    // review編集画面を表示
    public function showEditForm(int $project_id, int $task_id, int $prep_id, int $review_id)
    {
        // ログインユーザーに紐づく該当IDのreviewを取得
        $editing_review = Auth::user()->preps()->find($prep_id)->reviews()->find($review_id);
        $current_task = Auth::user()->tasks()->find($task_id);

        if (!empty($editing_review->started_at)) {
            $sa = new Carbon($editing_review->started_at);
        } else {
            // 記録がない場合は現在時刻から予定単位時間を引いた時間
            $sa = Carbon::now()->subMinutes($editing_review->prep->unit_time);
        }
        $started_date = $sa->toDateString();
        $started_time = $sa->format('H:i');

        return view('reviews.edit', compact('editing_review', 'current_task',  'started_date', 'started_time'));
    }

    // review編集処理
    public function edit(int $project_id, int $task_id, int $prep_id, int $review_id, EditReview $request)
    {
        // リクエストのIDからreviewデータを取得
        $editing_review = Auth::user()->reviews()->find($review_id);
        $current_prep = Auth::user()->preps()->find($prep_id);

        // 入力された日付、時刻からdateTimeを生成
        // $editing_review->started_at = Carbon::createFromFormat(
        //     'Y-m-d H:i',
        //     $request->started_date . ' ' . $request->started_time
        // );

        // 完了済みチェックがonの場合はタスクのステータスを4（完了）に切り替え
        if ($request->task_completed) {
            Auth::user()->projects()->find($project_id)->tasks()->where('id', $task_id)->update(['status' => 4]);
        }

        // 該当のreviewデータをフォームの入力値で書き換えて保存
        $current_prep->reviews()->save($editing_review->fill($request->all()));

        // 編集対象のreviewが属するreviewのreview一覧にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', '振り返りを変更しました');
    }

    // review削除処理
    public function delete(int $project_id, int $task_id, int $prep_id, int $review_id)
    {
        // リクエストで受け取ったIDのreviewを削除
        Auth::user()->preps()->find($prep_id)->reviews()->find($review_id)->delete();
        // review::find($review_id)->delete();
        // review::destroy($review_id);

        // 削除対象のreviewが属するreviewのreview一覧にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', '振り返りを削除しました');
    }

    // 記録追加画面を表示
    public function showAddForm()
    {
        // ログインユーザーに紐づく該当IDのレコードを取得
        $projects = Auth::user()->projects()->get();

        $dt = Carbon::now();
        // 開始時刻を日付と時刻に分ける
        $started_date = $dt->toDateString();
        $started_time = $dt->format('H:i');

        return view('reviews.add', compact('projects','started_date','started_time'));
    }

    // 記録追加処理
    public function add(AddReview $request)
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
        $prep->category_id = $request->category_id;
        Auth::user()->preps()->save($prep);

        // タスクのステータスを更新
        Auth::user()->tasks()->find($task->id)->update(['status' => 2]);

        $review = new Review();
        // 入力された日付、時刻からdateTimeを生成
        $review->started_at = Carbon::createFromFormat(
            'Y-m-d H:i',
            $request->started_date . ' ' . $request->started_time
        );

        // その他の項目をfillで登録
        $prep->reviews()->save($review->fill($request->all()));

        // 完了済みチェックがonの場合はタスクのステータスを4（完了）に切り替え
        if ($request->task_completed) {
            $task->update(['status' => 4]);
        } else {
            $task->update(['status' => 3]);
        }
        // 一覧画面にリダイレクト
        return redirect()->route('records.index')->with('flash_message', '記録を追加しました');
    }
}
