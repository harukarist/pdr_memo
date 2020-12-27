<?php

namespace App\Http\Controllers;

use App\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TotalController extends Controller
{
    // 記録追加画面を表示
    public function showCustomForm(Request $request)
    {
        $projects = Auth::user()->projects()->get();
        $categories = Auth::user()->categories()->get();

        // 現在時刻の取得用Carbon
        $today = Carbon::today();

        $report = new Report($today);
        $summaries = $report->getTotals($is_add = false);
        // dd($summaries);

        return view('totals.custom', compact('projects', 'categories', 'today', 'summaries'));
    }
    public function custom(Request $request)
    {
        $projects = Auth::user()->projects()->get();
        $categories = Auth::user()->categories()->get();

        // dd($request);
        if($projects){
            foreach($projects as $project){
                if($project->custom_hours !== $request->project_hours[$project->id]){
                    $project->custom_hours = $request->project_hours[$project->id];
                    $project->save();
                }
            }
        }
        if($categories){
            foreach($categories as $category){
                if($category->custom_hours !== $request->category_hours[$category->id]){
                    $category->custom_hours = $request->category_hours[$category->id];
                    $category->save();
                }
            }
        }
        return redirect()->route('reports.weekly')->with('flash_message', '合計時間を変更しました');
    }
}
