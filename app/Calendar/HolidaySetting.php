<?php

namespace App\Calendar;

use Yasumi\Yasumi; //祝日

//CalendarViewに休日を返す
class HolidaySetting
{
  private $holidays = null;

  // 指定した年の祝日を読み込んで、$holidaysプロパティに保持
  function loadHoliday($year)
  {
    $this->holidays = Yasumi::create("Japan", $year, "ja_JP");
  }

  function isHoliday($date)
  {
    // Yasumiオブジェクトが初期化されているか判定
    if (!$this->holidays) return false;
    // 読み込みできていれば祝日かどうかを判定
    return $this->holidays->isHoliday($date);
  }

  function getHolidayNames()
  {
    // Yasumiオブジェクトが初期化されているか判定
    if (!$this->holidays) return false;
    // 読み込みできていれば祝日名を取得
    $holidayNames = [];
    foreach ($this->holidays as $holiday) {
      $date_key = $holiday->format('Ymd');
      $holidayNames[$date_key] = $holiday->getName();
    }
    return $holidayNames;
  }
}
