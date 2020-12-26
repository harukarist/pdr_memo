<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DoController extends Controller
{
    // メモ保存処理
    public function postMemo(int $task_id, Request $request)
    {
        // リクエストのIDからタスクデータを取得
        $task = Auth::user()->tasks()->find($task_id);

        // 該当のタスクデータをフォームの入力値で書き換えて保存
        $task->memo_text = $request->memo_text;
        $task->save();
    }
}
