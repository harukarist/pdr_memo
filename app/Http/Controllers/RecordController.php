<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RecordController extends Controller
{
    // 記録一覧表示
    public function index()
    {
        $preps = Auth::user()->preps()->orderBy('id', 'desc')->get();

        // ログインユーザーに紐づく記録データを取得
        // $reviews = Auth::user()->tasks()->orderBy('created_at', 'desc')->paginate(10);
        // $reviews = Auth::user()->reviews()->orderBy('reviews.id', 'desc')->paginate(10);
        // dd($reviews);

        return view('records.index', compact('preps'));
    }

}
