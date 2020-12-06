<?php

namespace App\Calendar;

use Carbon\Carbon;

// その日のカレンダーを出力する
// CalendarWeekクラスから呼び出される
class CalendarWeekDay
{
  protected $carbon;
  protected $isHoliday = false;
  protected $isToday = false;
  public $holidayName = null;
  public $review = null;
  public $path = null;

  // コンストラクタ
  function __construct($date)
  {
    // 引数で指定された日付のオブジェクトを生成
    $this->carbon = new Carbon($date);
  }

  // データを取得するためのdate_keyを生成
  function getDateKey()
  {
    return $this->carbon->format("Ymd");
  }

  // 祝日かどうかを判定
  function checkHoliday(HolidaySetting $setting)
  {
    if ($setting->isHoliday($this->carbon)) {
      $this->isHoliday = true;
    }
  }
  // 今日かどうかを判定
  function checkToday($date)
  {
    if ($this->carbon->format("Ymd") === $date->format("Ymd")) {
      $this->isToday = true;
    }
  }

  // CSSクラス名を出力
  function getClassName()
  {
    // format()関数に「D」を指定して「Sun」「Mon」などの曜日を省略形式で取得
    // strtolower()で小文字に変換
    $classNames = ["day-" . strtolower($this->carbon->format("D"))];
    // 日曜日はday-sun、月曜日はday-monというクラス名を出力

    // 祝日の場合
    if ($this->isHoliday) {
      $classNames[] = "day-holiday";
    }
    // 今日の日付の場合
    if ($this->isToday) {
      $classNames[] = "day-today";
    }
    // レビューがある場合
    if ($this->review) {
      $classNames[] = "day-reviewed";
    }
    return implode(" ", $classNames);
  }

  /**
   * @return 
   */
  // カレンダーの日の内部を出力
  function render()
  {

    // format()関数に「j」を指定して、先頭にゼロをつけない日付を取得
    $day = [];

    $day[] = '<a href="' . $this->path . '?date=' . $this->carbon->format("Y-m-d") . ' " class="day-link">';
    $day[] = '<p class="day">' . $this->carbon->format("j") . '</p>';

    if ($this->holidayName) {
      $day[] = '<span class="day-name">' . $this->holidayName . '</span>';
    }
    if ($this->review) {
      $day[] = '<mark class="review-hour">' . $this->review->hour . ' h</mark>';
    }
    $day[] = '</a>';
    return implode("", $day);
  }
}
