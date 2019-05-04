<?php

namespace Tests\Unit\Domain\Workload;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Workload\Workload;
use Tests\Unit\Domain\Workload\faker\WorkloadFaker;

class WorkloadTest extends TestCase
{
    /**
     * @test
     */
    public function Workloadインスタンス()
    {
        $create = WorkloadFaker::create(1);

        $id = $create[0]->getId();
        $userId = $create[0]->getUserId();
        $projectId = $create[0]->getProjectId();
        $categoryId = $create[0]->getCategoryId();
        $amount = $create[0]->getAmount();
        $date = $create[0]->getDate();

        $workload = new Workload(
            $id,
            $userId,
            $projectId,
            $categoryId,
            $amount,
            $date
        );

        $this->assertSame($workload->getId(), $id);
        $this->assertSame($workload->getUserId(), $userId);
        $this->assertSame($workload->getProjectId(), $projectId);
        $this->assertSame($workload->getCategoryId(), $categoryId);
        $this->assertSame($workload->getAmount(), $amount);
        $this->assertSame($workload->getDate(), $date);
    }
}
