<?php

namespace App\Http\Controllers;

use App\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class RecordController extends Controller
{
    const CATEGORY = [
        1 => ['id' => 1, 'category_name' => 'Input', 'category_class' => 'badge-light'],
        2 => ['id' => 2, 'category_name' => 'Output', 'category_class' => 'badge-light'],
        3 => ['id' => 3, 'category_name' => 'Etc', 'category_class' => 'badge-light'],
    ];

    // 記録一覧表示
    public function index()
    {
        // ログインユーザIDを取得
        $user_id = Auth::getUser()->id;

        // 参考）1日ごとにSQLで実績時間を集計
        $records = DB::table('reviews')
            ->join('preps', 'preps.id', '=', 'reviews.prep_id')
            ->join('tasks', 'tasks.id', '=', 'preps.task_id')
            ->select(
                DB::raw('DATE_FORMAT(DATE_ADD(reviews.created_at,INTERVAL -3 HOUR),"%Y/%m/%d (%a)") as target_date'),
                DB::raw('ROUND(SUM(reviews.actual_time)/60,1) as hour'),
            )
            ->where('preps.user_id', '=', $user_id)
            ->groupby('target_date')
            ->get();

        $dones = DB::table('reviews')
            ->leftJoin('preps', 'preps.id', '=', 'reviews.prep_id')
            ->leftJoin('tasks', 'tasks.id', '=', 'preps.task_id')
            ->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->select(DB::raw('DATE_FORMAT(DATE_ADD(reviews.created_at,INTERVAL -3 HOUR),"%Y/%m/%d (%a)") as target_date'), 'task_name', 'status', 'review_text', 'actual_time', 'reviews.category_id')
            ->orderBy('target_date', 'DESC')
            ->where('preps.user_id', $user_id)
            ->where('reviews.deleted_at', null)
            ->where('tasks.deleted_at', null)
            ->get();

        $lists = [];
        $before_date = new Date;
        $before_task = '';
        $cnt = [];
        $tmp = 0;
        $dn = 0;
        $tn = 0;
        $lastIndex = count($dones) - 1;
        foreach ($dones as $index => $value) {
            // 最初の要素の場合は日付とタスク名を出力
            if (empty($lists)) {
                $lists[$dn]['target_date'] = $value->target_date;
                $lists[$dn]['tasks'][$tn]['task_name'] = $value->task_name;
                $lists[$dn]['tasks'][$tn]['task_status'] = $value->status;
            } else {
                // 日付が違う場合は日付と前日の合計時間を出力
                if ($before_date != $value->target_date) {
                    $dn++;
                    // echo ($value->target_date . '<br>');
                    $lists[$dn]['target_date'] = $value->target_date;
                    $lists[$dn - 1]['total_time'] = $cnt;
                    $cnt = [];
                }
                // タスク名が違う場合は出力
                if ($before_task != $value->task_name) {
                    $tn++;
                    // echo ('■' . $value->task_name . '<br>');
                    $lists[$dn]['tasks'][$tn]['task_name'] = $value->task_name;
                    $lists[$dn]['tasks'][$tn]['task_status'] = $value->status;
                }
                // 最後の要素の場合は合計時間を出力
                if ($index === $lastIndex) {
                    $cnt[$value->category_id] = $cnt[$value->category_id] + $value->actual_time;
                    $lists[$dn]['total_time'] = $cnt;
                }
            }
            // echo ($value->actual_time . '分 ');
            $lists[$dn]['tasks'][$tn]['reviews'][] = [
                'actual_time' => $value->actual_time,
                // 'review_text' => mb_substr($value->review_text, 0, 50),
                'review_text' => $value->review_text,
                'category' => self::CATEGORY[$value->category_id],
            ];
            // echo (mb_substr($value->review_text, 0, 50) . '<br>');
            $before_date = $value->target_date;
            $before_task = $value->task_name;
            if (isset($cnt[$value->category_id])) {
                $tmp = $cnt[$value->category_id];
            }
            $cnt[$value->category_id] =  $tmp + $value->actual_time;
            $tmp = 0;
        }
        $category = self::CATEGORY;

        // dd($lists,$lists[0]['tasks'][0]['task_status'],$lists[0]['target_date']);

        // //    $lists = Auth::user()->tasks()->whereYear('created_at', '2020')
        // //     ->whereMonth('created_at', '11')
        // //     ->orderBy('created_at')
        // //     ->get();
        // $reviewed_hours = Auth::user()->reviews()->sum('actual_time');
        // $reviewed_count = Auth::user()->reviews()->count('actual_time');

        // $preps = Auth::user()->preps()->get();
        // $remained_minutes = 0;
        // foreach ($preps as $prep) {
        //     $remained_minutes = $remained_minutes + ($prep->unit_time * $prep->estimated_steps);
        // }

        // // ログインユーザーに紐づく記録データを取得
        // // $reviews = Auth::user()->tasks()->orderBy('created_at', 'desc')->paginate(10);
        // // $reviews = Auth::user()->reviews()->orderBy('reviews.id', 'desc')->paginate(10);
        // // dd($reviews);

        // return view('components.total', compact('reviewed_hours', 'reviewed_count', 'remained_minutes'));
        return view('records.index', compact('category', 'records', 'lists'));
    }
}
