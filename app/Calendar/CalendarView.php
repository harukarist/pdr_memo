<?php
// 名前空間を設定
namespace App\Calendar;

use App\Report;
use Carbon\Carbon;
use App\Calendar\CalendarWeek;
use App\Calendar\HolidaySetting;
use App\Calendar\CalendarWeekDay;

// カレンダー表示の親クラス
class CalendarView
{
  protected $carbon;
  protected $reviews = []; //カテゴリ別実績時間のデータを保持
  protected $totals = []; //トータル時間のデータを保持
  protected $holidays = []; //祝日名を保持
  protected $path = null;
  protected $today;

  function __construct($date)
  {
    // コンストラクタで受け取った日付を元にCarbonオブジェクトを作成
    $this->carbon = new Carbon($date, 'Asia/Tokyo');

    // URIからクエリパラメータを除いたパスを取得
    $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // 今日の日付を取得
    $this->today = Carbon::now();
    $this->today->subHours(2);
  }

  /**
   * タイトルを生成
   */
  public function getTitle()
  {
    return $this->carbon->format('Y年n月');
  }

  /**
   * カレンダーを出力
   */
  function render()
  {
    // 休日オブジェクト
    $setting = new HolidaySetting();

    // 指定年の祝日を読み込み
    $setting->loadHoliday($this->carbon->format("Y"));
    $this->holidays = $setting->getHolidayNames();

    // 指定月の実績時間の読み込み
    $report = new Report($this->carbon->format("Y-m-d"));
    $this->reviews = $report->getTimeWithMonthByCategory($this->carbon->format("Y"), $this->carbon->format("m"));
    $this->totals = $report->getTimeWithMonth($this->carbon->format("Y"), $this->carbon->format("m"));
    // dd($this->totals, $this->reviews);


    //カレンダー最上段を描画
    $html = [];
    $html[] = '<div class="calendar">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th>土</th>';
    $html[] = '<th>日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';

    // 週カレンダー
    $html[] = '<tbody>';
    // 1ヶ月分の週カレンダーオブジェクトの配列を取得
    $weeks = $this->getWeeks();

    // 1週ずつ出力処理
    foreach ($weeks as $week) {
      $html[] = '<tr class="' . $week->getClassName() . '">';
      // 週カレンダーオブジェクトのメソッドで日カレンダーオブジェクトの配列を取得
      $days = $week->getDays($setting);
      // 1日ずつ出力処理
      foreach ($days as $day) {
        $html[] = $this->renderDay($day);
      }
      $html[] = '</tr>';
    }

    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';

    // 配列の要素を空文字で連結
    return implode("", $html);
  }

  /**
   * 日を描画
   */
  protected function renderDay(CalendarWeekDay $day)
  {
    $html = [];
    $html[] = '<td class="' . $day->getClassName() . '">';
    $html[] = $day->render(); //日付を描画
    $html[] = '</td>';

    return implode("", $html);
  }


  /**
   * 1ヶ月分の週を描画
   */
  protected function getWeeks()
  {
    $weeks = [];

    //月の開始日、最終日を取得
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();

    //1週目を取得
    $weeks[] = $this->getWeek($firstDay->copy());

    //1週目を+7日した後、週の開始日に移動して翌週の月曜日を取得
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();

    //月末まで1週ごとにループ
    while ($tmpDay->lte($lastDay)) {
      // count($weeks)で何週目かを指定して週を作成
      $weeks[] = $this->getWeek($tmpDay->copy(), count($weeks));
      //+7日して$tmpDayを翌週に移動
      $tmpDay->addDay(7);
    }

    return $weeks;
  }

  /**
   * @return CalendarWeek
   */
  // 週を作成
  protected function getWeek(Carbon $date, $index = 0)
  {
    // 週の開始日と週番号を渡して週を作成
    $week = new CalendarWeek($date, $index);
    $week->holidays = $this->holidays;
    $week->path = $this->path;
    $week->today = $this->today;
    $week->target_day = $this->carbon->copy();

    // 週の開始日〜終了日を取得
    $start = $date->copy()->startOfWeek()->format("Ymd");
    $end = $date->copy()->endOfWeek()->format("Ymd");

    // 週オブジェクトに該当週のレビュー情報をセット
    foreach ($this->reviews as $id => $review) {
      $week->reviews[$id] = $review->filter(
        function ($value, $key) use ($start, $end) {
          return $key >= $start && $key <= $end;
        }
      );
    }
    $week->totals = $this->totals->filter(
      function ($value, $key) use ($start, $end) {
        return $key >= $start && $key <= $end;
      }
    );
    return $week;
  }

  /**
   * 次の月を取得
   */
  public function getNext()
  {
    // addMonthsNoOverflow()で翌月の情報を取得
    return $this->carbon->copy()->addMonthsNoOverflow()->format('Y-m');
  }
  /**
   * 前の月を取得
   */
  public function getPrevious()
  {
    return $this->carbon->copy()->subMonthsNoOverflow()->format('Y-m');
  }
  /**
   * 選択中の日付を取得
   */
  public function getDate()
  {
    return $this->carbon->format('Y-m-d');
  }
}
