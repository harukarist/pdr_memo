<?php
// 名前空間を設定
namespace App\Calendar;

use App\Review;
use App\Project;
use Carbon\Carbon;

class ReportView
{
  protected $carbon;
  protected $lists = []; //レポートデータを保持

  function __construct($date)
  {
    $this->carbon = new Carbon($date, 'Asia/Tokyo');
  }

  function getWeeklyLists()
  {
    // 今週のReviewの読み込み
    // 週の開始日〜終了日を作成
    $startDay = $this->carbon->copy()->startOfWeek()->format("Y-m-d");
    $lastDay = $this->carbon->copy()->endOfWeek()->format("Y-m-d");

    $reviews = Review::getReportWeekly($startDay, $lastDay);
    // $reviews = Review::getReportMonthly($this->carbon->format("Y"), $this->carbon->format("m"));

    return $this->separateList($reviews);
  }

  function getDailyList()
  {
    $reviews = Review::getReportDaily($this->carbon);

    return $this->separateList($reviews);
  }

  function separateList($reviews)
  {
    $lists = [];
    $cnt = [];
    $tmp = [];
    $dn = 0;
    $tn = 0;
    $lastIndex = count($reviews) - 1;

    if (count($reviews)) {
      foreach ($reviews as $index => $value) {
        // 最初の要素の場合は日付とタスク名を出力
        if (empty($lists)) {
          $lists[$dn]['target_date'] = $value->target_date;
          $lists[$dn]['tasks'][$tn]['task_name'] = $value->task_name;
          $lists[$dn]['tasks'][$tn]['task_status'] = $value->status;
          $tmp['date'] = $value->target_date;
          $tmp['task'] = $value->task_name;
        } else {
          // 日付が違う場合は日付,タスクと、前日の合計時間を出力
          if ($tmp['date'] != $value->target_date) {
            $dn++;
            $tn = 0; //タスク番号をリセット
            // echo ($value->target_date . '<br>');
            $lists[$dn]['target_date'] = $value->target_date;
            $lists[$dn]['tasks'][$tn]['task_name'] = $value->task_name;
            $lists[$dn]['tasks'][$tn]['task_status'] = $value->status;
            $lists[$dn - 1]['total_time'] = $cnt;
            $cnt = [];
          } elseif ($tmp['task'] != $value->task_name) {
            // タスク名が違う場合は出力
            $tn++;
            // echo ('■' . $value->task_name . '<br>');
            $lists[$dn]['tasks'][$tn]['task_name'] = $value->task_name;
            $lists[$dn]['tasks'][$tn]['task_status'] = $value->status;
          }
          // 最後の要素の場合は合計時間を出力
          if ($index === $lastIndex) {
            if (isset($cnt[$value->category_id])) {
              $tmp['cnt'] = $cnt[$value->category_id];
              $cnt[$value->category_id] =  $tmp['cnt'] + $value->actual_time;
            } else {
              $cnt[$value->category_id] =  $value->actual_time;
            }
            $lists[$dn]['total_time'] = $cnt;
          }
        }
        // echo ($value->actual_time . '分 ');
        $lists[$dn]['tasks'][$tn]['reviews'][] = [
          'actual_time' => $value->actual_time,
          // 'review_text' => mb_substr($value->review_text, 0, 50),
          'review_text' => $value->review_text,
          'category' => Project::CATEGORIES[$value->category_id],
        ];
        // echo (mb_substr($value->review_text, 0, 50) . '<br>');
        $tmp['date'] = $value->target_date;
        $tmp['task'] = $value->task_name;
        if (isset($cnt[$value->category_id])) {
          $tmp['cnt'] = $cnt[$value->category_id];
          $cnt[$value->category_id] =  $tmp['cnt'] + $value->actual_time;
          $tmp['cnt'] = 0;
        } else {
          $cnt[$value->category_id] =  $value->actual_time;
        }
      }
    }

    return $lists;
  }
}
