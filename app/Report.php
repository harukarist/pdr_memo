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

  // //指定月の日別の実績時間を取得
  // public function getTimeWithMonthByProject($year, $month)
  // {

  //   if ($this->projects) {
  //     foreach ($this->projects as $project) {
  //       $time_by_project[$project->id] = DB::table('reviews')
  //         ->join('preps', 'preps.id', '=', 'reviews.prep_id')
  //         ->join('tasks', 'tasks.id', '=', 'preps.task_id')
  //         ->select(
  //           DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -2 HOUR),"%Y%m%d") as target_date'),
  //           DB::raw('ROUND(SUM(reviews.actual_time)/60,1) as hour'),
  //         )
  //         ->where('reviews.user_id', '=', $this->user_id)
  //         ->where('reviews.deleted_at', null)
  //         ->where('tasks.project_id', '=', $project->id)
  //         ->whereYear('started_at', '=', $year)
  //         ->whereMonth('started_at', '=', $month)
  //         ->groupBy('target_date')
  //         ->get()->keyBy('target_date');
  //     }
  //   } else {
  //     $time_by_project = '';
  //   }
  //   // dd($time_by_project);

  //   return $time_by_project;
  // }

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

  //カレンダー下に表示する指定週の日別のプロジェクトごとの実績時間を取得
  // public function getTimeWithWeekByProject()
  // {
  //   $startDay = $this->carbon->copy()->startOfWeek();
  //   $lastDay = $this->carbon->copy()->endOfWeek();

  //   if ($this->projects) {
  //     for ($i = 0; true; $i++) {
  //       foreach ($this->projects as $project) {
  //         $time_by_project[$startDay->format("Ymd")][$project->project_name] = DB::table('reviews')
  //           ->join('preps', 'preps.id', '=', 'reviews.prep_id')
  //           ->join('tasks', 'tasks.id', '=', 'preps.task_id')
  //           ->select(
  //             DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -2 HOUR),"%Y%m%d") as target_date'),
  //             DB::raw('ROUND(SUM(reviews.actual_time)/60,1) as hour'),
  //           )
  //           ->where('reviews.user_id', '=', $this->user_id)
  //           ->where('reviews.deleted_at', null)
  //           ->where('tasks.project_id', '=', $project->id)
  //           ->whereDate('started_at', '=', $startDay)
  //           ->groupBy('target_date')
  //           ->get()->keyBy('target_date');
  //       }
  //       // 週の最終日から1日ずつ週の頭までループ
  //       $date = $startDay->addDays(1);
  //       if ($date > $lastDay) {
  //         break;
  //       }
  //     }
  //   } else {
  //     $time_by_project = '';
  //   }

  //   // dd($time_by_project);
  //   return $time_by_project;
  // }


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


  // public function getTasksWithWeekByProject($startDay, $lastDay)
  // {
  //   

  //   foreach ($this->projects as $project) {
  //     $tasks_by_project[$project->id] = DB::table('tasks')
  //       ->join('preps', 'tasks.id', '=', 'preps.task_id')
  //       ->join('reviews', 'preps.id', '=', 'reviews.prep_id')
  //       ->where('reviews.user_id', '=', $this->user_id)
  //       ->where('tasks.project_id', '=', $project->id)
  //       ->whereDate('tasks.updated_at', '<=', $lastDay)
  //       ->whereDate('tasks.updated_at', '>=', $startDay)
  //       ->where('reviews.deleted_at', null)
  //       ->where('preps.deleted_at', null)
  //       ->where('tasks.deleted_at', null)
  //       ->orderBy('reviews.started_at', 'DESC')
  //       ->get()->keyBy('reviews.started_at');
  //   }
  //   dd($tasks_by_project);
  //   return $tasks_by_project;
  // }


  // プロジェクトごとの１日の実施タスクを取得
  // public function getTasksWithDayByProject($day)
  // {
  //   

  //   foreach ($this->projects as $project) {
  //     $tasks_by_project[$project->id] = Task::with('preps.reviews')
  //       ->where('tasks.project_id', '=', $project->id)
  //       ->where('tasks.status', '>=', '3')
  //       ->whereDate('tasks.updated_at', '=', $day)
  //       ->orderBy('tasks.updated_at', 'DESC')
  //       ->get();
  //   }
  //   dd($tasks_by_project);
  //   return $tasks_by_project;
  // }




  // public function getReportWeekly($startDay, $lastDay)
  // {
  //   $reports = DB::table('reviews')
  //     ->leftJoin('preps', 'preps.id', '=', 'reviews.prep_id')
  //     ->leftJoin('tasks', 'tasks.id', '=', 'preps.task_id')
  //     ->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
  //     ->select(DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -2 HOUR),"%Y/%m/%d (%a)") as target_date'), 'task_name', 'status', 'review_text', 'actual_time', 'reviews.category_id')
  //     ->orderBy('target_date', 'DESC')
  //     ->where('projects.user_id', $this->user_id)
  //     ->where('reviews.deleted_at', null)
  //     ->where('tasks.deleted_at', null)
  //     ->whereDate('started_at', '<=', $lastDay)
  //     ->whereDate('started_at', '>=', $startDay)
  //     ->get();
  //   return $reports;
  // }

  // public function getReportDaily($day)
  // {
  //   $reports = DB::table('reviews')
  //     ->leftJoin('preps', 'preps.id', '=', 'reviews.prep_id')
  //     ->leftJoin('tasks', 'tasks.id', '=', 'preps.task_id')
  //     ->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
  //     ->select(DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -2 HOUR),"%Y/%m/%d (%a)") as target_date'), 'task_name', 'status', 'review_text', 'actual_time', 'reviews.category_id')
  //     ->orderBy('target_date', 'DESC')
  //     ->where('projects.user_id', $this->user_id)
  //     ->where('reviews.deleted_at', null)
  //     ->where('tasks.deleted_at', null)
  //     ->whereDate('started_at', '=', $day)
  //     ->get();
  //   return $reports;
  // }

  //   public function getWeeklyReviews($startDay, $lastDay)
  //   {
  //     $reviews = Review::with('prep.task.project')
  //       ->whereDate('started_at', '<=', $lastDay)
  //       ->whereDate('started_at', '>=', $startDay)
  //       ->get();
  //     return $reviews;
  //   }
  //   public function getDailyReviews($day)
  //   {
  //     $projects = Project::with('tasks.preps.reviews')->where('projects.user_id', $this->user_id)
  //       ->whereDate('started_at', '=', $day)
  //       ->get();
  //     return $projects;
  //   }

  //   public function getUserProjects()
  //   {
  //     $projects = Project::with('tasks.preps.reviews')
  //       ->where('projects.user_id', $this->user_id)
  //       ->get();
  //     return $projects;
  //   }
  // }
}
