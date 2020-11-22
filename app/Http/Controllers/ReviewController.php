<?php

namespace App\Http\Controllers;

use App\Review;
use Illuminate\Http\Request;
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

        // ログインユーザーに紐づくカテゴリーを入力フォーム用に取得
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
}
