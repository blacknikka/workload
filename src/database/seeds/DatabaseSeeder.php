<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(WorkloadFKeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(WorkloadTableSeeder::class);
        $this->call(DepartmentTableSeeder::class);
    }
}
