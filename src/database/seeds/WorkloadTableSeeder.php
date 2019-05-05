<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class WorkloadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $today = Carbon::today();
        $now = Carbon::now()->format('Y-m-d H:i:s');

        // project
        $project1 = [
            'id' => 1,
            'name' => 'proj1',
            'comment' => 'sample project',
        ];
        DB::table('project')->insert([$project1]);

        // category
        $category1 = [
            'id' => 1,
            'name' => 'category1',
            'comment' => 'sample category',
        ];
        DB::table('category')->insert([$category1]);

        $workload1 = [
            'id' => 100,
            'user_id' => 1,
            'project_id' => 1,
            'category_id' => 1,
            'amount' => 2.5,
            'date' => $today,
            'created_at' => $now,
            'updated_at' => $now,
        ];
        $workload2 = [
            'id' => 101,
            'user_id' => 1,
            'project_id' => 1,
            'category_id' => 1,
            'amount' => 3,
            'date' => $today,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        DB::table('workload')->insert([$workload1, $workload2]);
    }
}
