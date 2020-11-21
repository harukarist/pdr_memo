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

        if (is_null($project)) {
            return view('home');
        }
        return redirect()->route('tasks.index', [
            'project_id' => $project->id
        ]);
    }
}
