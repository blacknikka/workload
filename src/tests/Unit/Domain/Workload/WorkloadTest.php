<?php

namespace Tests\Unit\Domain\Workload;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Workload\Workload;
use Tests\Unit\Domain\Workload\faker\WorkloadFaker;
use Faker\Generator as Faker;

class WorkloadTest extends TestCase
{
    /**
     * @test
     */
    public function Workloadインスタンス()
    {
        $faker = app()->make(Faker::class);

        $create = WorkloadFaker::create(1);

        $id = $create[0]->getId();
        $project = $create[0]->getProject();
        $category = $create[0]->getCategory();
        $amount = $create[0]->getAmount();
        $date = $create[0]->getDate();

        $workload = new Workload(
            $id,
            $project,
            $category,
            $amount,
            $date
        );

        $this->assertSame($workload->getId(), $id);
        $this->assertSame($workload->getProject(), $project);
        $this->assertSame($workload->getCategory(), $category);
        $this->assertSame($workload->getAmount(), $amount);
        $this->assertSame($workload->getDate(), $date);
    }
}
