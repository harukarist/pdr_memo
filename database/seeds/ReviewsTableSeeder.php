<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $record = DB::table('records')->first();
        $category = DB::table('categories')->first();

        foreach (range(1, 3) as $num) {
            DB::table('reviews')->insert([
                'review_text' => "Reviewテキスト{$num}",
                'good_text' => "goodテキスト{$num}",
                'problem_text' => "problemテキスト{$num}",
                'try_text' => "tryテキスト{$num}",
                'actual_time' => 35,
                'step_counter' => $num,
                'record_id' => $record->id,
                'category_id' => $category->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
