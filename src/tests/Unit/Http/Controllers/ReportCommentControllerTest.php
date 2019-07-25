<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Infrastructure\Db\ReportCommentDao;
use Mockery;
use App\Domain\Report\ReportComment;
use Illuminate\Support\Collection;
use Tests\Unit\Domain\Report\faker\ReportCommentFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Carbon\Carbon;
use Illuminate\Http\Response;

class ReportCommentControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @var Mockery\MockInterface */
    private $reportCommentDaoMock;

    public function setUp()
    {
        parent::setUp();

        // 依存をモックする
        $this->reportCommentDaoMock = Mockery::mock(ReportCommentDao::class);

        // 注入
        app()->instance(ReportCommentDao::class, $this->reportCommentDaoMock);
    }

    public function tearDown()
    {
        parent::tearDown();

        // モックをクローズ
        Mockery::close();
    }

    /** @test */
    public function getReportCommentById_findがnullの場合()
    {
        $this->reportCommentDaoMock
            ->shouldReceive('find')
            ->andReturn(null);

        $response = $this->getJson(
            route(
                'getReportCommentById',
                [
                    'id' => 1,
                ]
            )
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $this->reportCommentDaoMock->shouldHaveReceived('find', [1]);
    }

    /** @test */
    public function getReportCommentById_findが値を返す場合()
    {
        $reportComment = ReportCommentFaker::create(1)[0];

        $this->reportCommentDaoMock
            ->shouldReceive('find')
            ->andReturn($reportComment);

        $response = $this->getJson(
            route(
                'getReportCommentById',
                [
                    'id' => 1,
                ]
            )
        );

        $response->assertStatus(Response::HTTP_OK);
        $this->reportCommentDaoMock->shouldHaveReceived('find', [1]);
    }
}
