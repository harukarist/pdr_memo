<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrepsTableSeeder extends Seeder
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
            DB::table('preps')->insert([
                'prep_text' => "Prepテキスト{$num}",
                'unit_time' => 30,
                'estimated_steps' => $num,
                'record_id' => $record->id,
                'category_id' => $category->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
