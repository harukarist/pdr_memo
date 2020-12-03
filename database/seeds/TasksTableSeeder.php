<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project = DB::table('projects')->first();

        foreach (range(1, 4) as $num) {
            DB::table('tasks')->insert([
                'task_name' => "サンプルタスク {$num}",
                'due_date' => Carbon::now()->addDay($num),
                'status' => $num,
                'project_id' => $project->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
