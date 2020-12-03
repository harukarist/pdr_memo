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
        $prep = DB::table('preps')->first();
        $category = DB::table('categories')->first();

        foreach (range(1, 3) as $num) {
            DB::table('reviews')->insert([
                'review_text' => "Reviewテキスト{$num}",
                'good_text' => "goodテキスト{$num}",
                'problem_text' => "problemテキスト{$num}",
                'try_text' => "tryテキスト{$num}",
                'started_at' => Carbon::now(),
                'actual_time' => 35,
                'prep_id' => $prep->id,
                'category_id' => $category->id,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ]);
        }
    }
}
