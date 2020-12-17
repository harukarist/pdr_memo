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
    // dd($total_time);
    // dd($records);


    $dt = new Carbon($date);
    $startDay = $dt->copy()->startOfWeek()->format("Y/m/d(D)");
    $lastDay = $dt->copy()->endOfWeek()->format("Y/m/d(D)");

    // 作成したオブジェクトをViewに渡し、View上でrenderメソッドを実行
    return view('reports.weekly', compact('weekly', 'lists', 'summaries', 'startDay', 'lastDay','date'));
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
    // dd($lists);

    $summaries = $report->getSummaries();

    // 作成したオブジェクトをViewに渡し、View上でrenderメソッドを実行
    return view('reports.daily', compact('weekly', 'lists', 'summaries','date'));
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
    return view('reports.calendar', compact('calendar', 'lists','date'));
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

  // タスク検索処理
  public function search(Request $request)
  {
    $keyword = $request->input('keyword');
    $query = User::query();

    if (!empty($keyword)) {
      $query->where('task_name', 'like', '%' . $keyword . '&')->orWhere('status', 'like', '%' . $keyword . '&');
    }
    $data = $query->orderBy('priority', 'desc')->orderBy('due_date', 'desc')->orderBy('updated_at', 'desc')
      ->paginate(15);
  }
}
