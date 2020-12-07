<?php

namespace App\Http\Controllers;

use App\Report;
use App\Review;
use App\Project;
use Carbon\Carbon;
use App\Calendar\ReportView;
use Illuminate\Http\Request;
use App\Calendar\CalendarView;
use App\Http\Controllers\Controller;

class CalendarController extends Controller
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

    // 指定月のカレンダーを生成
    $calendar = new CalendarView($date);

    // タスク一覧を取得
    $report = new Report($date);
    $lists = $report->getReviewsWithDay();


    // 作成したオブジェクトをViewに渡す
    return view('reports.calendar', compact('calendar', 'lists'));
  }
}
