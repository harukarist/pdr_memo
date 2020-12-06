<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;
use Carbon\Carbon;
use App\Calendar\ReportView;
use App\Calendar\WeeklyView;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WeeklyController extends Controller
{
  public function show(Request $request)
  {

    //Requestのinput()でクエリーのdateを受け取る
    $date = $request->input("date");

    if ($date && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])$/", $date)) {
      //dateがYYYY-MMの場合はYYYY-MM-01に変換
      $date = $date . "-01";
    } elseif (!($date && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/", $date))) {
      //dateがYYYY-MM-DDでない場合はnull
      $date = null;
    }

    //取得出来ない時は今日の日付を取得
    if (!$date) $date = Carbon::today();

    //WeeklyViewクラスで指定週のカレンダーを生成
    $weekly = new WeeklyView($date);
    $report = new ReportView($date);
    $lists = $report->getWeeklyLists();

    $user_id = Auth::id();
    $projects = Project::with('tasks.preps.reviews')->where('projects.user_id', $user_id)->get();

    // dd($projects);
    $category = Project::CATEGORIES;


    // 作成したオブジェクトをViewに渡す
    return view('reports.weekly', compact('weekly', 'lists', 'projects', 'category'));
  }

  // public function day(Request $request)
  // {

  //   //Requestのinput()でクエリーのdateを受け取る
  //   $date = $request->input("date");

  //   //dateがYYYY-MM-ddの形式かどうか判定する
  //   if ($date && preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $date)) {
  //     // $date = $date . "-01";
  //     $date = $date;
  //   } else {
  //     $date = null;
  //   }

  //   //取得出来ない時は今日の日付を取得
  //   if (!$date) $date = Carbon::today();

  //   //WeeklyViewクラスで指定月のカレンダーを生成
  //   $weekly = new WeeklyView($date);
  //   $report = new ReportView($date);
  //   $lists = $report->getDailyList();

  //   $category = Project::CATEGORIES;


  //   // 作成したオブジェクトをViewに渡す
  //   return view('reports.weekly', compact('weekly', 'lists', 'category'));
  // }
}
