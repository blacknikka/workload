<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\WorkloadController;
use App\Infrastructure\Db\WorkloadDao;
use Illuminate\Http\Response;
use Mockery;
use App\Domain\Workload\Workload;
use Illuminate\Support\Collection;
use Tests\Unit\Domain\Workload\faker\WorkloadFaker;

class WorkloadControllerTest extends TestCase
{
    /** @var Mockery\MockInterface */
    private $workloadDaoMock;

    public function setUp()
    {
        parent::setUp();

        // 依存をモックする
        $this->workloadDaoMock = Mockery::mock(WorkloadDao::class);

        // 注入
        app()->instance(WorkloadDao::class, $this->workloadDaoMock);
    }

    public function tearDown()
    {
        parent::tearDown();

        // モックをクローズ
        Mockery::close();
    }

    /** @test */
    public function getWorkloadById_WorkloadDaoのfindが呼ばれている()
    {
        $this->workloadDaoMock
            ->shouldReceive('find')
            ->andReturn(null);

        $response = $this->getJson('api/workload/get/id/1');
        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $this->workloadDaoMock->shouldHaveReceived('find', [1]);
    }

    /** @test */
    public function getWorkloadById_findの結果がnullのケース()
    {
        $this->workloadDaoMock
            ->shouldReceive('find')
            ->andReturn(null);

        $response = $this->getJson('api/workload/get/id/1');
        $response
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson([]);
    }

    /** @test */
    public function getWorkloadById_findの結果が存在するケース()
    {
        $workload = WorkloadFaker::create(1)[0];

        $this->workloadDaoMock
            ->shouldReceive('find')
            ->andReturn($workload);

        $response = $this->getJson('api/workload/get/id/1');
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'id' => $workload->getId(),
                'project_id' => $workload->getProjectId(),
                'category_id' => $workload->getCategoryId(),
                'amount' => $workload->getAmount(),
                'date' => $workload->getDate()->toIso8601String(),
            ]);
    }
}
