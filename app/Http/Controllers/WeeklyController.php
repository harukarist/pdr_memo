<?php

namespace App\Http\Controllers;

use App\Prep;
use App\Task;
use App\Report;
use App\Review;
use App\Project;
use Carbon\Carbon;
use App\Calendar\ReportView;
use App\Calendar\WeeklyView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WeeklyController extends Controller
{
  public function showWeekly(Request $request)
  {
    $date = $this->getDate($request);

    //WeeklyViewクラスで指定週のカレンダーを生成
    $weekly = new WeeklyView($date);

    // タスク一覧を取得
    $report = new Report($date);
    $lists = $report->getReviewsWithWeek();
    $records = $report->getTimeWithWeekByProject();

    // 作成したオブジェクトをViewに渡し、View上でrenderメソッドを実行
    return view('reports.weekly', compact('weekly', 'lists','records'));
  }





  public function showDaily(Request $request)
  {
    $date = $this->getDate($request);

    //WeeklyViewクラスで指定週のカレンダーを生成
    $weekly = new WeeklyView($date);

    // タスク一覧を取得
    $report = new Report($date);
    $lists = $report->getReviewsWithDay();


    // 作成したオブジェクトをViewに渡し、View上でrenderメソッドを実行
    return view('reports.weekly', compact('weekly', 'lists'));
  }


  // 対象の日付を設定
  public function getDate(Request $request)
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

    return $date;
  }
}
