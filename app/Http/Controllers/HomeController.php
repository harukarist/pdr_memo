<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // ログインユーザのプロジェクトを1件取得
        $project = Auth::user()->projects()->first();

        // プロジェクト未登録の場合はホーム画面を表示
        if (is_null($project)) {
            return view('home');
        }
        // プロジェクト登録済みの場合はそのプロジェクトのタスク一覧を表示
        // フラッシュメッセージがある場合はセッションも渡す
        if (session('flash_message')) {
            return redirect()->route('tasks.index', [
                'project_id' => $project->id
            ])->with('flash_message', session('flash_message'));
        }
        return redirect()->route('tasks.index', [
            'project_id' => $project->id
        ]);
    }
}
