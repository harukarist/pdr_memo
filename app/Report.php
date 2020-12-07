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
    $this->categories = Project::CATEGORIES;
    $this->carbon = new Carbon($date, 'Asia/Tokyo');
  }


  //指定月の日別の実績時間を取得
  public function getTimeWithMonth($year, $month)
  {
    $time = DB::table('reviews')
      ->join('preps', 'preps.id', '=', 'reviews.prep_id')
      ->join('tasks', 'tasks.id', '=', 'preps.task_id')
      ->select(
        DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -3 HOUR),"%Y%m%d") as target_date'),
        DB::raw('ROUND(SUM(reviews.actual_time)/60,1) as hour'),
        DB::raw('ROUND(SUM(flow_level)/COUNT(flow_level),1) as flow_level')
      )
      ->where('preps.user_id', '=', $this->user_id)
      ->where('reviews.deleted_at', null)
      ->whereYear('started_at', '=', $year)
      ->whereMonth('started_at', '=', $month)
      ->groupBy('target_date')
      ->get()->keyBy('target_date');

    return $time;
  }

  //指定月の日別の実績時間を取得
  public function getTimeWithMonthByProject($year, $month)
  {
    $projects = Auth::user()->projects;

    foreach ($projects as $project) {
      $time_by_project[$project->id] = DB::table('reviews')
        ->join('preps', 'preps.id', '=', 'reviews.prep_id')
        ->join('tasks', 'tasks.id', '=', 'preps.task_id')
        ->select(
          DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -3 HOUR),"%Y%m%d") as target_date'),
          DB::raw('ROUND(SUM(reviews.actual_time)/60,1) as hour'),
        )
        ->where('preps.user_id', '=', $this->user_id)
        ->where('reviews.deleted_at', null)
        ->where('tasks.project_id', '=', $project->id)
        ->whereYear('started_at', '=', $year)
        ->whereMonth('started_at', '=', $month)
        ->groupBy('target_date')
        ->get()->keyBy('target_date');
    }
    // dd($time_by_project);

    return $time_by_project;
  }

  // カレンダーに表示するカテゴリー別の時間表示
  public function getTimeWithMonthByCategory($year, $month)
  {
    // カテゴリー配列をループして各idをキーとした配列に分けて集計
    foreach ($this->categories as $category) {
      $time_by_category[$category['id']] = DB::table('reviews')
        ->join('preps', 'preps.id', '=', 'reviews.prep_id')
        ->join('tasks', 'tasks.id', '=', 'preps.task_id')
        ->select(
          DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -3 HOUR),"%Y%m%d") as target_date'),
          DB::raw('ROUND(SUM(reviews.actual_time)/60,1) as hour'),
        )
        ->where('preps.user_id', '=', $this->user_id)
        ->where('reviews.deleted_at', null)
        ->where('reviews.category_id', '=', $category['id'])
        ->whereYear('started_at', '=', $year)
        ->whereMonth('started_at', '=', $month)
        ->groupBy('target_date')
        ->get()->keyBy('target_date');
    }
    // dd($time_by_category);

    return $time_by_category;
  }


  //指定週の日別の実績時間を取得
  public function getTimeWithWeek($startDay, $lastDay)
  {
    $actual_times = DB::table('reviews')
      ->join('preps', 'preps.id', '=', 'reviews.prep_id')
      ->join('tasks', 'tasks.id', '=', 'preps.task_id')
      ->select(
        DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -3 HOUR),"%Y%m%d") as target_date'),
        DB::raw('ROUND(SUM(reviews.actual_time)/60,1) as hour'),
        DB::raw('ROUND(SUM(flow_level)/COUNT(flow_level),1) as flow_level')
      )
      ->where('preps.user_id', '=', $this->user_id)
      ->where('reviews.deleted_at', null)
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
    // カテゴリー配列をループして各idをキーとした配列に分けて集計
    foreach ($this->categories as $category) {
      $time_by_category[$category['id']] = DB::table('reviews')
        ->join('preps', 'preps.id', '=', 'reviews.prep_id')
        ->join('tasks', 'tasks.id', '=', 'preps.task_id')
        ->select(
          DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -3 HOUR),"%Y%m%d") as target_date'),
          DB::raw('ROUND(SUM(reviews.actual_time)/60,1) as hour'),
        )
        ->where('preps.user_id', '=', $this->user_id)
        ->where('reviews.deleted_at', null)
        ->where('reviews.category_id', '=', $category['id'])
        ->whereDate('started_at', '<=', $lastDay)
        ->whereDate('started_at', '>=', $startDay)
        ->groupBy('target_date')
        ->get()->keyBy('target_date');
    }
    // dd($time_by_category);
    return $time_by_category;
  }

  //カレンダー下に表示する指定週の日別のプロジェクトごとの実績時間を取得
  public function getTimeWithWeekByProject()
  {
    $startDay = $this->carbon->copy()->startOfWeek();
    $lastDay = $this->carbon->copy()->endOfWeek();

    $projects = Auth::user()->projects;

    for ($i = 0; true; $i++) {
      foreach ($projects as $project) {
        $time_by_project[$startDay->format("Ymd")][$project->project_name] = DB::table('reviews')
          ->join('preps', 'preps.id', '=', 'reviews.prep_id')
          ->join('tasks', 'tasks.id', '=', 'preps.task_id')
          ->select(
            DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -3 HOUR),"%Y%m%d") as target_date'),
            DB::raw('ROUND(SUM(reviews.actual_time)/60,1) as hour'),
          )
          ->where('preps.user_id', '=', $this->user_id)
          ->where('reviews.deleted_at', null)
          ->where('tasks.project_id', '=', $project->id)
          ->whereDate('started_at', '=', $startDay->format("Y-m-d"))
          ->groupBy('target_date')
          ->get()->keyBy('target_date');
      }
      $date = $startDay->addDays(1);
      if ($date > $lastDay) {
        break;
      }
    }

    // dd($time_by_project);
    return $time_by_project;
  }


  // カレンダーの下に表示する1日ごとの１週間の実施タスクを取得
  public function getReviewsWithWeek()
  {
    $startDay = $this->carbon->copy()->startOfWeek();
    $lastDay = $this->carbon->copy()->endOfWeek();

    for ($i = 0; true; $i++) {
      $reviews_with_week[$lastDay->format('Y/m/d(D)')] = Review::with('prep.task.project')
        ->whereDate('started_at', '=', $lastDay->format("Y-m-d"))
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
    $reviews_with_day[$day->format('Y/m/d(D)')] = Review::with('prep.task.project')
      ->whereDate('started_at', '=', $day->format("Y-m-d"))
      ->orderBy('started_at', 'DESC')
      ->get();

    // dd($reviews_with_day);
    return $reviews_with_day;
  }


  // public function getTasksWithWeekByProject($startDay, $lastDay)
  // {
  //   $projects = Auth::user()->projects;

  //   foreach ($projects as $project) {
  //     $tasks_by_project[$project->id] = DB::table('tasks')
  //       ->join('preps', 'tasks.id', '=', 'preps.task_id')
  //       ->join('reviews', 'preps.id', '=', 'reviews.prep_id')
  //       ->where('preps.user_id', '=', $this->user_id)
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
  //   $projects = Auth::user()->projects;

  //   foreach ($projects as $project) {
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
  //     ->select(DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -3 HOUR),"%Y/%m/%d (%a)") as target_date'), 'task_name', 'status', 'review_text', 'actual_time', 'reviews.category_id')
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
  //     ->select(DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -3 HOUR),"%Y/%m/%d (%a)") as target_date'), 'task_name', 'status', 'review_text', 'actual_time', 'reviews.category_id')
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
