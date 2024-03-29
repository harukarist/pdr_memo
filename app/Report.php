<?php

namespace App;

use App\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Report
{
  public $user_id = null;
  public $categories = [];
  protected $carbon;

  function __construct($date)
  {
    $this->user_id = Auth::getUser()->id;
    $this->categories = Auth::user()->categories;
    $this->projects = Auth::user()->projects;
    $this->carbon = new Carbon($date, 'Asia/Tokyo');
  }

  public function getTotals($is_add = true)
  {
    // 対象の日付を取得
    $day = $this->carbon->copy();

    $records = DB::table('reviews')
      ->join('preps', 'preps.id', '=', 'reviews.prep_id')
      ->join('tasks', 'tasks.id', '=', 'preps.task_id')
      ->select('actual_time', 'started_at', 'tasks.project_id', 'reviews.category_id')
      ->where('reviews.user_id', '=', $this->user_id)
      ->where('reviews.deleted_at', null)
      ->where('tasks.deleted_at', null)
      ->where('started_at', '<=', $day->addHours(26)->format("Y-m-d H:i:s"))
      ->orderBy('started_at', 'ASC')
      ->get();

    // 最初の達成日を取得
    if (count($records)) {
      $started_at = $records->first()->started_at;
      $df = new Carbon($started_at);
      $total_time['total'] = [
        'days_count' => $df->diffInDays($day) + 1,
        'started_at' => $df->format('Y/m/d(D)'),
      ];
    } else {
      return '';
    }
    // dd($total_time);

    // プロジェクト作成済みの場合はデータを集計
    // プロジェクトが未作成の場合、$this->projectsはnull
    if ($this->projects) {
      foreach ($this->projects as $project) {
        // プロジェクト別
        $project_records = $records->where('project_id', '=', $project->id);
        // dd($project_records);
        if (count($project_records)) {
          $total_time['projects'][$project->project_name]
            = round(($project_records->sum('actual_time')) / 60, 1);
        } else {
          $total_time['projects'][$project->project_name]
            = 0;
        }
      }
      // 記録に追加する時間数があれば加算する
      if ($is_add && $project->custom_hours) {
        $total_time['projects'][$project->project_name]
          += $project->custom_hours;
      }
    }
    // カテゴリー作成済みの場合はデータを集計
    if ($this->categories) {
      foreach ($this->categories as $category) {
        // カテゴリ別
        $category_records = $records->where('category_id', '=', $category->id);
        // dd($category_records);
        if (count($category_records)) {
          $total_time['categories'][$category->category_name]
            = round(($category_records->sum('actual_time')) / 60, 1);
        } else {
          $total_time['categories'][$category->category_name]
            = 0;
        }
        // 記録に追加する時間数があれば加算する
        if ($is_add && $category->custom_hours) {
          $total_time['categories'][$category->category_name]
            += $category->custom_hours;
        }
      }
    } else {
      $total_time = '';
    }
    // dd($total_time);
    return $total_time;
  }

  //カレンダー上に表示する合計実績を取得
  public function getSummaries()
  {
    // 対象の日付を取得
    $day = $this->carbon->copy();

    $records = DB::table('reviews')
      ->join('preps', 'preps.id', '=', 'reviews.prep_id')
      ->join('tasks', 'tasks.id', '=', 'preps.task_id')
      ->select('actual_time', 'started_at', 'tasks.status', 'tasks.project_id', 'reviews.category_id')
      ->where('reviews.user_id', '=', $this->user_id)
      ->where('reviews.deleted_at', null)
      ->where('tasks.deleted_at', null)
      ->where('started_at', '<=', $day->addHours(26)->format("Y-m-d H:i:s"))
      ->orderBy('started_at', 'ASC')
      ->get();


    // 最初の達成日を取得
    if (count($records)) {
      $started_at = $records->first()->started_at;
      $df = new Carbon($started_at);
      $total_time['total'] =
        [
          'days_count' => $df->diffInDays($day) + 1,
          'started_at' => $df->format('Y/m/d(D)'),
        ];
    } else {
      return '';
    }

    // プロジェクト作成済みの場合はデータを集計
    // プロジェクトが未作成の場合、$this->projectsはnull
    if ($this->projects) {
      foreach ($this->projects as $project) {
        // プロジェクト別
        $project_records = $records->where('project_id', '=', $project->id);

        // dd($project_records);

        if (count($project_records)) {
          $started_at = $project_records->first()->started_at;
          // dd($started_at);
          // $dt = Carbon::tomorrow();
          $df = new Carbon($started_at);

          $total_time['projects'][$project->project_name] =
            [
              'total_hour' => round(($project_records->sum('actual_time')) / 60, 1),
              'total_count' => $project_records->count('actual_time'),
              'completed_count' => $project_records->where('status', '=', 4)->count(),
              'days_count' => $df->diffInDays($day) + 1,
              'started_at' => $df->format('Y/m/d(D)'),
            ];

          // 各プロジェクトの中で、カテゴリーごとの内訳を取得
          if ($this->categories) {
            foreach ($this->categories as $category) {
              $total_time['projects'][$project->project_name]['categories'][$category->category_name] =
                [
                  'total_hour' => round(($project_records->where('category_id', '=', $category->id)->sum('actual_time')) / 60, 1),
                  'total_count' => $project_records->where('category_id', '=', $category->id)->count('actual_time'),
                  'completed_count' => $project_records->where('category_id', '=', $category->id)->where('status', '=', 4)->count(),
                ];
            }
          }
        }
      }
    }
    // カテゴリー作成済みの場合はデータを集計
    if ($this->categories) {
      foreach ($this->categories as $category) {
        // カテゴリ別
        $category_records = $records->where('category_id', '=', $category->id);
        // dd($category_records);
        if (count($category_records)) {
          $started_at = $category_records->first()->started_at;
          // dd($started_at);
          // $dt = Carbon::tomorrow();
          $df = new Carbon($started_at);

          $total_time['categories'][$category->category_name] =
            [
              'total_hour' => round(($category_records->sum('actual_time')) / 60, 1),
              'total_count' => $category_records->count('actual_time'),
              'completed_count' => $category_records->where('status', '=', 4)->count(),
              'days_count' => $df->diffInDays($day) + 1,
              'started_at' => $df->format('Y/m/d'),
            ];
        }
      }
    } else {
      $total_time = '';
    }
    // dd($total_time);
    return $total_time;
  }

  //指定月の日別の実績時間を取得
  public function getTimeWithMonth($year, $month)
  {
    $time = DB::table('reviews')
      ->select(
        DB::raw('DATE_FORMAT(DATE_ADD(started_at,INTERVAL -2 HOUR),"%Y%m%d") as target_date'),
        DB::raw('ROUND(SUM(actual_time)/60,1) as hour'),
        DB::raw('ROUND(SUM(flow_level)/COUNT(flow_level),1) as flow_level')
      )
      ->where('user_id', '=', $this->user_id)
      ->where('deleted_at', null)
      ->whereYear('started_at', '=', $year)
      ->whereMonth('started_at', '=', $month)
      ->groupBy('target_date')
      ->get()->keyBy('target_date');

    return $time;
  }

  // カレンダーに表示するカテゴリー別の時間表示
  public function getTimeWithMonthByCategory($year, $month)
  {
    if ($this->categories) {
      // カテゴリー配列をループして各idをキーとした配列に分けて集計
      foreach ($this->categories as $category) {
        $time_by_category[$category->id] = DB::table('reviews')
          ->select(
            DB::raw('DATE_FORMAT(DATE_ADD(started_at,INTERVAL -2 HOUR),"%Y%m%d") as target_date'),
            DB::raw('ROUND(SUM(actual_time)/60,1) as hour'),
          )
          ->where('user_id', '=', $this->user_id)
          ->where('deleted_at', null)
          ->where('category_id', '=', $category->id)
          ->whereYear('started_at', '=', $year)
          ->whereMonth('started_at', '=', $month)
          ->groupBy('target_date')
          ->get()->keyBy('target_date');
      }
    } else {
      $time_by_category = '';
    }
    // dd($time_by_category);

    return $time_by_category;
  }


  //指定週の日別の実績時間を取得
  public function getTimeWithWeek($startDay, $lastDay)
  {
    $actual_times = DB::table('reviews')
      ->select(
        DB::raw('DATE_FORMAT(DATE_ADD(started_at,INTERVAL -2 HOUR),"%Y%m%d") as target_date'),
        DB::raw('ROUND(SUM(actual_time)/60,1) as hour'),
        DB::raw('ROUND(SUM(flow_level)/COUNT(flow_level),1) as flow_level')
      )
      ->where('user_id', '=', $this->user_id)
      ->where('deleted_at', null)
      ->whereDate('started_at', '<=', $lastDay)
      ->whereDate('started_at', '>=', $startDay)
      ->groupby('target_date')
      ->get()->keyBy('target_date');

    return $actual_times;
  }

  // カレンダーに表示する時間表示
  //指定週の日別のカテゴリー別の実績時間を取得
  public function getTimeWithWeekByCategory($startDay, $lastDay)
  {
    if ($this->categories) {
      // カテゴリー配列をループして各idをキーとした配列に分けて集計
      foreach ($this->categories as $category) {
        $time_by_category[$category->id] = DB::table('reviews')
          ->select(
            DB::raw('DATE_FORMAT(DATE_ADD(started_at,INTERVAL -2 HOUR),"%Y%m%d") as target_date'),
            DB::raw('ROUND(SUM(actual_time)/60,1) as hour'),
          )
          ->where('user_id', '=', $this->user_id)
          ->where('deleted_at', null)
          ->where('category_id', '=', $category->id)
          ->whereDate('started_at', '<=', $lastDay)
          ->whereDate('started_at', '>=', $startDay)
          ->groupBy('target_date')
          ->get()->keyBy('target_date');
      }
    } else {
      $time_by_category = '';
    }
    // dd($time_by_category);
    return $time_by_category;
  }


  // カレンダーの下に表示する1日ごとの１週間の実施タスクを取得
  public function getReviewsWithWeek()
  {
    $startDay = $this->carbon->copy()->startOfWeek(); //月曜日の0:00
    $lastDay = $this->carbon->copy()->endOfWeek()->startOfDay(); //日曜日の23:59:59を0:00に修正
    for ($i = 0; true; $i++) {
      $reviews_with_week[$lastDay->format('Y/m/d(D)')]
        = Review::with('prep.task.project')
        ->where('reviews.user_id', '=', $this->user_id)
        ->where('reviews.deleted_at', null)
        // ->whereDate('started_at', '=', $lastDay->format("Y-m-d"))
        ->where('started_at', '>', $lastDay->copy()->addHours(2)->format("Y-m-d H:i:s"))
        ->where('started_at', '<=', $lastDay->copy()->addHours(26)->format("Y-m-d H:i:s"))
        ->orderBy('started_at', 'DESC')
        ->get();

      // 週の最終日から1日ずつ週の頭までループ
      $date = $lastDay->subDays(1);
      if ($date < $startDay) {
        break;
      }
    }
    // dd($reviews_with_week);
    return $reviews_with_week;
  }

  // カレンダーの下に表示する1日ごとの１週間の実施タスクを取得
  public function getReviewsWithDay()
  {
    $day = $this->carbon->copy();
    $reviews_with_day[$day->format('Y/m/d(D)')]
      = Review::with('prep.task.project')
      ->where('reviews.user_id', '=', $this->user_id)
      ->where('reviews.deleted_at', null)
      // ->whereDate('started_at', '=', $day->format("Y-m-d"))
      ->where('started_at', '>', $day->addHours(2)->format("Y-m-d H:i:s"))
      ->where('started_at', '<=', $day->addHours(26)->format("Y-m-d H:i:s"))
      ->orderBy('started_at', 'DESC')
      ->get();


    // dd($reviews_with_day);
    return $reviews_with_day;
  }
}
