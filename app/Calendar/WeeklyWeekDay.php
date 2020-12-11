<?php

namespace App\Calendar;

use App\Project;
use App\Category;
use Carbon\Carbon;
use App\Calendar\HolidaySetting;
use App\Calendar\CalendarWeekDay;
use Illuminate\Support\Facades\Auth;

// その日のカレンダーを出力する
// CalendarWeekクラスから呼び出される
class WeeklyWeekDay extends CalendarWeekDay
{
  /**
   * @return 
   */
  // カレンダーの日の内部を出力
  function render()
  {
    // format()関数に「j」を指定して、先頭にゼロをつけない日付を取得
    $day = [];

    $day[] = '<a href="/reports/daily?date=' . $this->carbon->format("Y-m-d") . ' " class="day-link">';
    $day[] = '<p class="day">' . $this->carbon->format("j") . '</p>';

    if ($this->holidayName) {
      $day[] = '<span class="day-name">' . $this->holidayName . '</span>';
    }
    if (isset($this->total_hour)) {
      $day[] = '<p class="total-hour">';
      $day[] = '<mark class="text-primary">
      <i class="fas fa-stopwatch" aria-hidden="true"></i>' .
        $this->total_hour . ' h</mark>';
      $day[] = '<mark class="text-success">
      <i class="fas fa-spa" aria-hidden="true"></i>' .
        $this->flow_level .  '</mark>';
      $day[] = '</p>';
    }
    if (isset($this->reviews)) {
      foreach ($this->reviews as $id => $review) {
        $day[] = '<div class="category-wrapper category-' . $id . '"><p class="category-name mb-1">' .
          $this->categories[$id]['category_name'] . '</p>';
        $day[] = '<p class="category-hour">' .
          $review->hour . ' h</p></div><br>';
      }
    }

    $day[] = '</a>';
    return implode("", $day);
  }
}
