<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\WorkloadController;
use App\Infrastructure\Db\WorkloadDao;
use Illuminate\Http\Response;
use Mockery;

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
}
