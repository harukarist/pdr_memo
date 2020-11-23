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
    // Review登録画面を表示
    public function showCreateForm(int $prep_id)
    {
        // ログインユーザーに紐づく該当IDのPrepを取得
        $done_prep = Auth::user()->preps()->find($prep_id);

        // ログインユーザーに紐づくデータを入力フォーム用に取得
        $categories = Auth::user()->categories()->get();

        // 該当Prepに紐づくReview回数の最大値を取得
        $review_count = $done_prep->reviews->max('step_counter') + 1;

        return view('reviews.create', compact('done_prep', 'categories', 'review_count'));
    }

    // review登録処理
    public function create(int $prep_id, CreateReview $request)
    {
        // ログインユーザーに紐づく該当IDのPrepレコードを取得
        $current_prep =  Auth::user()->preps()->find($prep_id);

        // 該当のPrepに紐づくReviewレコードを登録
        $review = new Review();
        $current_prep->reviews()->save($review->fill($request->all()));

        // 一覧画面にリダイレクト
        return redirect()->route('records.index');
    }

    // review編集画面を表示
    public function showEditForm(int $prep_id, int $review_id)
    {
        // ログインユーザーに紐づく該当IDのreviewを取得
        // $editing_review = Review::find($review_id);
        $editing_review = Auth::user()->preps()->find($prep_id)->reviews()->find($review_id);

        // ログインユーザーに紐づくタスク、カテゴリーを入力フォーム用に取得
        $categories = Auth::user()->categories()->get();

        return view('reviews.edit', compact('editing_review', 'categories'));
    }

    // review編集処理
    public function edit(int $prep_id, int $review_id, EditReview $request)
    {
        // リクエストのIDからreviewデータを取得
        $editing_review = Auth::user()->preps()->find($prep_id)->reviews()->find($review_id);

        // 該当のreviewデータをフォームの入力値で書き換えて保存
        Auth::user()->preps()->find($prep_id)->reviews()->save($editing_review->fill($request->all()));

        // 編集対象のreviewが属するreviewのreview一覧にリダイレクト
        return redirect()->route('records.index')->with('flash_message', '振り返りを変更しました');
    }

    // review削除処理
    public function delete(int $prep_id, int $review_id)
    {
        // リクエストで受け取ったIDのreviewを削除
        Auth::user()->preps()->find($prep_id)->reviews()->find($review_id)->delete();
        // review::find($review_id)->delete();
        // review::destroy($review_id);

        // 削除対象のreviewが属するreviewのreview一覧にリダイレクト
        return redirect()->route('records.index')->with('flash_message', '振り返りを削除しました');
    }
}
