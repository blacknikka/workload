<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        $dep1 = [
            'id' => 1,
            'name' => 'dep1',
            'section_name' => 'sec1',
            'comment' => 'comment1',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        $dep2 = [
            'id' => 2,
            'name' => 'dep2',
            'section_name' => 'sec1',
            'comment' => 'comment2',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        DB::table('department')->insert([$dep1, $dep2]);
    }
}
