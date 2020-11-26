<?php

namespace App\Http\Controllers;

use App\Review;
use Illuminate\Http\Request;
use App\Http\Requests\EditReview;
use App\Http\Requests\CreateReview;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // ステータスの定義x
    const CATEGORY = [
        1 => ['id' => 1, 'category_name' => 'Input', 'category_class' => 'badge-primary'],
        2 => ['id' => 2, 'category_name' => 'Output', 'category_class' => 'badge-success'],
        3 => ['id' => 3, 'category_name' => 'Etc', 'category_class' => 'badge-secondary'],
    ];

    // Review登録画面を表示
    public function showCreateForm(int $project_id, int $task_id, int $prep_id)
    {
        // ログインユーザーに紐づく該当IDのレコードを取得
        $current_task = Auth::user()->tasks()->find($task_id);
        $done_prep = $current_task->preps()->find($prep_id);
        $categories = self::CATEGORY;

        // 該当Prepに紐づくReview回数の最大値を取得
        $review_count = $done_prep->reviews->max('step_counter') + 1;

        return view('reviews.create', compact('done_prep', 'current_task', 'categories', 'review_count'));
    }

    // review登録処理
    public function create(int $project_id, int $task_id, int $prep_id, CreateReview $request)
    {
        // リクエストのIDからprepデータを取得
        $current_prep = Auth::user()->preps()->find($prep_id);

        // 完了済みチェックがonの場合はタスクのステータスを3（完了）に切り替え
        if ($request->task_completed) {
            Auth::user()->projects()->find($project_id)->tasks()->where('id', $task_id)->update(['status' => 3]);
        }

        // 該当のPrepに紐づくReviewレコードを登録
        $review = new Review();
        $review->prep_id = $prep_id;
        $current_prep->reviews()->save($review->fill($request->all()));

        // 一覧画面にリダイレクト
        return redirect()->route('tasks.index', ['project_id' => $project_id])->with('flash_message', 'Reviewを登録しました');
    }

    // review編集画面を表示
    public function showEditForm(int $project_id, int $task_id, int $prep_id, int $review_id)
    {
        // ログインユーザーに紐づく該当IDのreviewを取得
        $editing_review = Auth::user()->preps()->find($prep_id)->reviews()->find($review_id);
        $current_task = Auth::user()->tasks()->find($task_id);
        $categories = self::CATEGORY;

        return view('reviews.edit', compact('editing_review', 'current_task', 'categories'));
    }

    // review編集処理
    public function edit(int $project_id, int $task_id, int $prep_id, int $review_id, EditReview $request)
    {
        // リクエストのIDからreviewデータを取得
        $editing_review = Auth::user()->reviews()->find($review_id);
        $current_prep = Auth::user()->preps()->find($prep_id);

        // 完了済みチェックがonの場合はタスクのステータスを3（完了）に切り替え
        if ($request->task_completed) {
            Auth::user()->projects()->find($project_id)->tasks()->where('id', $task_id)->update(['status' => 3]);
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
}
