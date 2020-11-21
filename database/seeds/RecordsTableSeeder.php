<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = DB::table('users')->first();
        $task = DB::table('tasks')->first();
        
        foreach (range(1, 3) as $num) {
            DB::table('records')->insert([
                'user_id' => $user->id,
                'target_date' => Carbon::now(),
                'task_id' => $task->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
