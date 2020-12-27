<?php

namespace App\Http\Controllers;

use App\Report;
use Carbon\Carbon;
use App\Calendar\WeeklyView;
use Illuminate\Http\Request;
use App\Calendar\CalendarView;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
  public function showWeekly(Request $request)
  {
    // 対象の日付を取得
    $date = $this->getDate($request);

    //WeeklyViewクラスで指定週のカレンダーを生成
    $weekly = new WeeklyView($date);

    // タスク一覧を取得
    $report = new Report($date);
    $lists = $report->getReviewsWithWeek();
    $summaries = $report->getSummaries();
    // dd($lists);
    // dd($summaries);

    $dt = new Carbon($date);
    $startDay = $dt->copy()->startOfWeek()->format("Y/m/d(D)");
    $lastDay = $dt->copy()->endOfWeek()->format("Y/m/d(D)");

    // 作成したオブジェクトをViewに渡し、View上でrenderメソッドを実行
    return view('reports.weekly', compact('weekly', 'lists', 'summaries', 'startDay', 'lastDay', 'date'));
  }

  public function showDaily(Request $request)
  {
    // 対象の日付を取得
    $date = $this->getDate($request);

    //WeeklyViewクラスで指定週のカレンダーを生成
    $weekly = new WeeklyView($date);

    // タスク一覧を取得
    $report = new Report($date);
    $lists = $report->getReviewsWithDay();
    $summaries = $report->getSummaries();
    // dd($lists);
    // dd($summaries);

    // 作成したオブジェクトをViewに渡し、View上でrenderメソッドを実行
    return view('reports.daily', compact('weekly', 'lists', 'summaries', 'date'));
  }

  public function showCalendar(Request $request)
  {
    // 対象の日付を取得
    $date = $this->getDate($request);

    // 指定月のカレンダーを生成
    $calendar = new CalendarView($date);

    // タスク一覧を取得
    $report = new Report($date);
    $lists = $report->getReviewsWithDay();


    // 作成したオブジェクトをViewに渡す
    return view('reports.calendar', compact('calendar', 'lists', 'date'));
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

    // 日付指定がない場合は今日の日付を取得
    if (!$date) {
      // 現在日時を取得
      $now = Carbon::now();
      if ($now->hour <= 2) {
        // 深夜2時まではその日に含める
        $date = Carbon::yesterday();
      } else {
        $date = Carbon::today();
      }
    }

    return $date;
  }
}
