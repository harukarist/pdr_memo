<?php
namespace App\Calendar;

use App\Calendar\CalendarWeekDay;

/**
* 余白を出力するためのクラス(CalendarWeekDayを継承)
*/
// CalendarWeekから呼ばれる

// 日カレンダーCalendarWeekDayをカスタマイズして
// クラス名とHTMLだけ別の処理になるようなクラスを作成
// クラス名は「day-blank」、render()で何も出力しないように上書き
class CalendarWeekBlankDay extends CalendarWeekDay {
	
    function getClassName(){
		return "day-blank";
	}

	/**
	 * @return 
	 */
	function render(){
		return '';
	}

}
