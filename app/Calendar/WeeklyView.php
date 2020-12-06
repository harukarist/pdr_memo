<?php

namespace App\Calendar;

use App\Review;
use Carbon\Carbon;
use App\Calendar\CalendarView;
use App\Calendar\HolidaySetting;

// 週次レポート
class WeeklyView extends CalendarView
{

  /**
   * タイトルを生成
   */
  public function getTitle()
  {
    $startDay = $this->carbon->copy()->startOfWeek()->format("Y年n月");
    $lastDay = $this->carbon->copy()->endOfWeek()->format("Y年n月");
    if ($startDay === $lastDay) {
      return $startDay;
    } else {
      return $startDay . "-" . $lastDay;
    }
  }


  /**
   * カレンダーを出力
   */
  function render()
  {
    // 休日オブジェクト
    $setting = new HolidaySetting();

    // 今年の祝日を読み込み
    $setting->loadHoliday($this->carbon->format("Y"));
    $this->holidays = $setting->getHolidayNames();

    // 週の開始日〜終了日を作成
    $startDay = $this->carbon->copy()->startOfWeek()->format("Y-m-d");
    $lastDay = $this->carbon->copy()->endOfWeek()->format("Y-m-d");

    // 指定週のReviewの読み込み
    $this->reviews = Review::getSumTimeWeekly($startDay, $lastDay);

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
    // 週カレンダーオブジェクトの配列を取得
    $week = $this->getWeek($this->carbon);
    $html[] = '<tr class="' . $week->getClassName() . '">';
    // 週カレンダーオブジェクトのメソッドで日カレンダーオブジェクトの配列を取得
    $days = $week->getDays($setting);
    // 1日ずつ出力処理
    foreach ($days as $day) {
      $html[] = $this->renderDay($day);
    }
    $html[] = '</tr>';

    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';

    return implode("", $html);
  }
  /**
   * @return CalendarWeek
   */
  // 週を作成
  protected function getWeek(Carbon $date, $index = 0)
  {
    $week = new WeeklyWeek($date, $index);
    $week->holidays = $this->holidays;
    $week->path = $this->path;
    $week->today = $this->today;
    $week->reviews = $this->reviews;
    return $week;
  }

  /**
   * 次の月を取得
   */
  public function getNextWeek()
  {
    // addWeeksNoOverflow()で翌月の情報を取得
    return $this->carbon->copy()->addWeek()->format('Y-m-d');
  }
  /**
   * 前の月を取得
   */
  public function getPreviousWeek()
  {
    return $this->carbon->copy()->subWeek()->format('Y-m-d');
  }
}
