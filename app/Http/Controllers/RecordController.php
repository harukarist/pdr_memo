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
        // ログインユーザーに紐づく記録データを取得
        $records = Auth::user()->records()->orderBy('target_date', 'desc')->paginate(10);

        return view('records.index', compact('records'));
    }

}
