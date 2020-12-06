<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    // ソフトデリート用のトレイトを追加
    use SoftDeletes;

    // ロックをかけないカラム
    protected $fillable = ['review_text', 'good_text', 'problem_text', 'try_text', 'flow_level', 'actual_time', 'category_id', 'started_at'];

    const FLOW_LEVEL = [
        1 => ['value' => 1, 'level_name' => '集中できなかった'],
        2 => ['value' => 2, 'level_name' => 'やや集中できなかった'],
        3 => ['value' => 3, 'level_name' => 'まあまあ'],
        4 => ['value' => 4, 'level_name' => '集中できた'],
        5 => ['value' => 5, 'level_name' => 'とても集中できた'],
    ];

    // 日時のフォーマット
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format("m/d H:i");
    }
    public function getStartedAtAttribute($value)
    {
        return Carbon::parse($value)->format("m/d H:i");
    }
    public function getFinishedAtAttribute($value)
    {
        return Carbon::parse($value)->format("H:i");
    }

    // リレーション
    public function prep()
    {
        return $this->belongsTo('App\Prep');
    }
    public function category()
    {
        return $this->belongsTo('App\Category');
    }


    public static function getSumTimeMonthly($year, $month)
    {
        $user_id = Auth::getUser()->id;

        $actual_times = DB::table('reviews')
            ->join('preps', 'preps.id', '=', 'reviews.prep_id')
            ->select(
                DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -3 HOUR),"%Y%m%d") as target_date'),
                DB::raw('ROUND(SUM(reviews.actual_time)/60,1) as hour'),
            )
            ->where('preps.user_id', '=', $user_id)
            ->whereYear('started_at', '=', $year)
            ->whereMonth('started_at', '=', $month)
            ->groupby('target_date')
            ->get()->keyBy('target_date');

        return $actual_times;
    }
    public static function getSumTimeWeekly($startDay, $lastDay)
    {
        $user_id = Auth::getUser()->id;

        $actual_times = DB::table('reviews')
            ->join('preps', 'preps.id', '=', 'reviews.prep_id')
            ->join('tasks', 'tasks.id', '=', 'preps.task_id')
            ->select(
                DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -3 HOUR),"%Y%m%d") as target_date'),
                DB::raw('ROUND(SUM(reviews.actual_time)/60,1) as hour'),
            )
            ->where('preps.user_id', '=', $user_id)
            ->whereDate('started_at', '<=', $lastDay)
            ->whereDate('started_at', '>=', $startDay)
            ->groupby('target_date')
            ->get()->keyBy('target_date');

        return $actual_times;
    }
    public static function getReportMonthly($year, $month)
    {
        $user_id = Auth::getUser()->id;

        $reports = DB::table('reviews')
            ->leftJoin('preps', 'preps.id', '=', 'reviews.prep_id')
            ->leftJoin('tasks', 'tasks.id', '=', 'preps.task_id')
            ->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->select(DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -3 HOUR),"%Y/%m/%d (%a)") as target_date'), 'task_name', 'status', 'review_text', 'actual_time', 'reviews.category_id')
            ->orderBy('target_date', 'DESC')
            ->where('projects.user_id', $user_id)
            ->where('reviews.deleted_at', null)
            ->where('tasks.deleted_at', null)
            ->whereYear('started_at', '=', $year)
            ->whereMonth('started_at', '=', $month)
            ->get();
        return $reports;
    }
    public static function getReportWeekly($startDay, $lastDay)
    {
        $user_id = Auth::getUser()->id;

        $reports = DB::table('reviews')
            ->leftJoin('preps', 'preps.id', '=', 'reviews.prep_id')
            ->leftJoin('tasks', 'tasks.id', '=', 'preps.task_id')
            ->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->select(DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -3 HOUR),"%Y/%m/%d (%a)") as target_date'), 'task_name', 'status', 'review_text', 'actual_time', 'reviews.category_id')
            ->orderBy('target_date', 'DESC')
            ->where('projects.user_id', $user_id)
            ->where('reviews.deleted_at', null)
            ->where('tasks.deleted_at', null)
            ->whereDate('started_at', '<=', $lastDay)
            ->whereDate('started_at', '>=', $startDay)
            ->get();
        return $reports;
    }
    public static function getReportDaily($day)
    {
        $user_id = Auth::getUser()->id;

        $reports = DB::table('reviews')
            ->leftJoin('preps', 'preps.id', '=', 'reviews.prep_id')
            ->leftJoin('tasks', 'tasks.id', '=', 'preps.task_id')
            ->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->select(DB::raw('DATE_FORMAT(DATE_ADD(reviews.started_at,INTERVAL -3 HOUR),"%Y/%m/%d (%a)") as target_date'), 'task_name', 'status', 'review_text', 'actual_time', 'reviews.category_id')
            ->orderBy('target_date', 'DESC')
            ->where('projects.user_id', $user_id)
            ->where('reviews.deleted_at', null)
            ->where('tasks.deleted_at', null)
            ->whereDate('started_at', '=', $day)
            ->get();
        return $reports;
    }
}
