<?php

namespace App\Http\Controllers;

use App\Review;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RecordController extends Controller
{
    // 記録一覧表示
    public function index()
    {
        // //    $test = Auth::user()->tasks()->whereYear('created_at', '2020')
        // //     ->whereMonth('created_at', '11')
        // //     ->orderBy('created_at')
        // //     ->get();
        // $reviewed_hours = Auth::user()->reviews()->sum('actual_time');
        // $reviewed_count = Auth::user()->reviews()->count('actual_time');

        // $preps = Auth::user()->preps()->get();
        // $remained_minutes = 0;
        // foreach ($preps as $prep) {
        //     $remained_minutes = $remained_minutes + ($prep->unit_time * $prep->estimated_steps);
        // }

        // // ログインユーザーに紐づく記録データを取得
        // // $reviews = Auth::user()->tasks()->orderBy('created_at', 'desc')->paginate(10);
        // // $reviews = Auth::user()->reviews()->orderBy('reviews.id', 'desc')->paginate(10);
        // // dd($reviews);

        // return view('components.total', compact('reviewed_hours', 'reviewed_count', 'remained_minutes'));
    }
}
