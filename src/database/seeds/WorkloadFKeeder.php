<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Schema\Blueprint;

class WorkloadFKeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::table('workload', function (Blueprint $table) {
            $table->foreign('project_id')
                ->references('id')
                ->on('project');
        });

        Schema::table('workload', function (Blueprint $table) {
            $table->foreign('category_id')
                ->references('id')
                ->on('category');
        });
    }
}
