<?php

namespace App\Http\Controllers;

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

    if ($date && preg_match("/^[0-9]{4}-[0-9]{2}$/", $date)) {
      //dateがYYYY-MMの場合はYYYY-MM-01に変換
      $date = $date . "-01";
    } elseif ($date && preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $date)) {
      //dateがYYYY-MM-DDの場合はそのまま
      $date = $date;
    } else {
      $date = null;
    }

    //取得出来ない時は今日の日付を取得
    if (!$date) $date = Carbon::today();

    // 指定月のカレンダーを生成
    $calendar = new CalendarView($date);
    // 指定日の記録を表示
    $report = new ReportView($date);
    $lists = $report->getDailyList();

    $category = Project::CATEGORIES;


    // 作成したオブジェクトをViewに渡す
    return view('reports.calendar', compact('calendar', 'lists', 'category'));
  }
}
