<?php
namespace App\Calendar;

use Carbon\Carbon;
use App\Calendar\HolidaySetting;
use App\Calendar\CalendarWeekDay;
use App\Calendar\CalendarWeekBlankDay;

// 週のカレンダーを出力
class WeeklyWeek extends CalendarWeek
{
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
			//カレンダー日オブジェクトを生成
			$days[] = $this->getDay($tmpDay->copy(), $setting);

			//翌日に移動
			$tmpDay->addDay(1);
		}
		return $days;
	}
}
