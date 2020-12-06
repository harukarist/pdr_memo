<?php

namespace App\Calendar;

use Carbon\Carbon;
use App\Calendar\HolidaySetting;
use App\Calendar\CalendarWeekDay;
use App\Calendar\CalendarWeekBlankDay;

// 週のカレンダーを出力
class CalendarWeek
{
	protected $carbon;
	protected $index = 0;

	public $holidays = [];
	public $reviews = [];
	public $path = null;
	public $today;
	public $target_day;

	function __construct($date, $index = 0)
	{
		$this->carbon = new Carbon($date);
		$this->index = $index;
	}

	// CSSクラス名を出力
	function getClassName()
	{
		return "week-" . $this->index;
	}

	/**
	 * @return CalendarWeekDay[]
	 */
	// 週の開始日〜終了日までを作成
	function getDays(HolidaySetting $setting)
	{
		$days = [];
		//週の開始日〜終了日を作成
		$startDay = $this->carbon->copy()->startOfWeek();
		$lastDay = $this->carbon->copy()->endOfWeek();

		//作業用
		$tmpDay = $startDay->copy();

		//開始日から終了日（月曜日〜日曜日）までループさせて作成
		while ($tmpDay->lte($lastDay)) {

			//前月、翌月の場合は空白を表示
			if ($tmpDay->month != $this->carbon->month) {
				// 違う月の場合は余白用のカレンダー日オブジェクトを追加
				$day = new CalendarWeekBlankDay($tmpDay->copy());
				$days[] = $day;
				//翌日に移動
				$tmpDay->addDay(1);
				continue;
			}

			//同じ月の場合は以下の関数で通常のカレンダー日オブジェクトを生成
			$days[] = $this->getDay($tmpDay->copy(), $setting);

			//翌日に移動
			$tmpDay->addDay(1);
		}

		return $days;
	}

	/**
	 * @return CalendarWeekDay
	 */
	function getDay(Carbon $date, HolidaySetting $setting)
	{
		// カレンダー日オブジェクトを生成
		$day = new CalendarWeekDay($date);
		$day->path = $this->path;

		// 休日判定
		$day->checkHoliday($setting);
		$day->checkToday($this->today);
		$day->checkTarget($this->target_day);

		// 該当日の休日情報があれば渡す
		if (isset($this->holidays[$day->getDateKey()])) {
			$day->holidayName = $this->holidays[$day->getDateKey()];
		}
		// 該当日のレビュー情報があれば渡す
		if (isset($this->reviews[$day->getDateKey()])) {
			$day->review = $this->reviews[$day->getDateKey()];
		}

		return $day;
	}
}
