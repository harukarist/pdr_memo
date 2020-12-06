<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = DB::table('users')->first();
        $names = ['PDRメモアプリ制作', '読書', '仕事'];
        $category = DB::table('categories')->first();

        foreach ($names as $name) {
            DB::table('projects')->insert([
                'project_name' => $name,
                'project_color' => '6cb2eb',
                'user_id' => $user->id,
                'category_id' => $category->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
