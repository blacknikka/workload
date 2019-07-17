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
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Carbon\Carbon;

class WorkloadControllerTest extends TestCase
{
    use WithoutMiddleware;

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
        $response->assertStatus(Response::HTTP_BAD_REQUEST);

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
            ->assertStatus(Response::HTTP_BAD_REQUEST)
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
                'user_id' => $workload->getUserId(),
                'project_id' => $workload->getProjectId(),
                'category_id' => $workload->getCategoryId(),
                'amount' => $workload->getAmount(),
                'date' => $workload->getDate()->toIso8601String(),
            ]);
    }

    /** @test */
    public function getWorkloadByUserId_WorkloadDaoのfindByUserIdが呼ばれている()
    {
        $this->workloadDaoMock
            ->shouldReceive('findByUserId')
            ->andReturn(collect());

        $response = $this->getJson('api/workload/get/user_id/1');
        $response->assertStatus(Response::HTTP_OK);

        $this->workloadDaoMock->shouldHaveReceived('findByUserId', [1]);
    }

    /** @test */
    public function getWorkloadByUserId_findByUserIdの結果がnullのケース()
    {
        $this->workloadDaoMock
            ->shouldReceive('findByUserId')
            ->andReturn(collect());

        $response = $this->getJson('api/workload/get/user_id/1');
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([]);
    }

    /** @test */
    public function getWorkloadByUserId_findByUserIdの結果が存在するケース()
    {
        $workload = WorkloadFaker::create(1);

        $this->workloadDaoMock
            ->shouldReceive('findByUserId')
            ->andReturn(collect($workload));

        // 使用するUserIDは適当（MockしているのでなんでもOK）
        $response = $this->getJson('api/workload/get/user_id/1');

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                [
                    'id' => $workload[0]->getId(),
                    'user_id' => $workload[0]->getuserId(),
                    'project_id' => $workload[0]->getProjectId(),
                    'category_id' => $workload[0]->getCategoryId(),
                    'amount' => $workload[0]->getAmount(),
                    'date' => $workload[0]->getDate()->toIso8601String(),
                ],
            ]);
    }

    /** @test */
    public function getWorkloadByWeeks_findByWeekDayが呼ばれている()
    {
        $workload = WorkloadFaker::create(1);
        $this->workloadDaoMock
            ->shouldReceive('findByWeekDay')
            ->andReturn(collect($workload));

        $response = $this->getJson(
            route(
                'getWorkloadByWeek',
                [
                    'id' => 1,
                    'week' => '2019-07-08',
                ]
            )
        );

        $response->assertStatus(Response::HTTP_OK);

        $this->workloadDaoMock->shouldHaveReceived(
            'findByWeekDay',
            [1, Carbon::class]
        );
    }

    /** @test */
    public function setWorkloadByUserId_WorkloadDaoのsaveがNGのケース()
    {
        $this->workloadDaoMock
            ->shouldReceive('save')
            ->andReturn(-1);

        $response = $this->postJson(
            'api/workload/set/user_id',
            [
                'user_id' => 1,
                'project_id' => 1,
                'category_id' => 1,
                'amount' => 1,
                'date' => '2018-05-01',
            ]
        );
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'result' => 'error',
                'message' => 'save error',
            ]);

        $this->workloadDaoMock->shouldHaveReceived('save');
    }

    /** @test */
    public function setWorkloadByUserId_saveの結果がOKのケース()
    {
        $this->workloadDaoMock
            ->shouldReceive('save')
            ->andReturn(1);

        $response = $this->postJson(
            'api/workload/set/user_id',
            [
                'user_id' => 1,
                'project_id' => 1,
                'category_id' => 1,
                'amount' => 1,
                'date' => '2018-05-01',
            ]
        );

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'result' => 'done',
                'message' => 'no error',
            ]);
    }

    /** @test */
    public function getWorkloadByMonth_データが取れる()
    {
        $userId = 10;
        $base = new Carbon('2019-07-01');

        $workloads = collect()::times(
            100,
            function ($index) use ($userId, $base) {
                $date = (new Carbon($base))->addDays($index);

                $workload =  WorkloadFaker::create(1, $userId, $date)[0];
                return $workload;
            }
        );

        $this->workloadDaoMock
            ->shouldReceive('findByMonth')
            ->andReturn($workloads);

            // ->with($userId, $base)
            // ->withArgs([$userId, $base])         // これでもOKらしい

        $response = $this->getJson(
            route(
                'getWorkloadByMonth',
                [
                    'id' => $userId,
                    'month' => '2019-07',
                ]
            )
        );

        $response
            ->assertStatus(Response::HTTP_OK);

        $contents = json_decode($response->getContent());
        $this->assertSame($contents->message, 'done');

        collect($contents->data)->each(
            function ($content, $index) use ($workloads) {
                $this->assertSame(
                    $content->id,
                    $workloads[$index]->getId()
                );

                $this->assertSame(
                    $content->user_id,
                    $workloads[$index]->getUserId()
                );

                $this->assertSame(
                    $content->project_id,
                    $workloads[$index]->getProjectId()
                );

                $this->assertSame(
                    $content->category_id,
                    $workloads[$index]->getCategoryId()
                );

                // キャストしないと小数点の差でテストが落ちる
                $this->assertSame(
                    (float)$content->amount,
                    $workloads[$index]->getAmount()
                );

                $this->assertSame(
                    $content->date,
                    $workloads[$index]->getDate()->toIso8601String()
                );
            }
        );
    }

    /** @test */
    public function update_WorkloadDaoのfindByUserIdが呼ばれている()
    {
        $this->workloadDaoMock
            ->shouldReceive('updateSeveralData')
            ->andReturn(
                [
                    'result' => true,
                    'saveResult' => [1]
                ]
            );

        $response = $this->postJson(
            route('updateWorkloadByUserId'),
            [
                'user_id' => 1,
                'workloads' => [
                    [
                        'id' => 2,
                        'project_id' => 3,
                        'category_id' => 4,
                        'amount' => 5,
                        'date' => '2019-7-14',
                    ]
                ],
            ]
        );
        $response->assertStatus(Response::HTTP_OK);
        $this->workloadDaoMock->shouldHaveReceived('updateSeveralData')
            ->once();
    }

    // -------------------------
    // 追加（コントローラーとは関係ないが、Carbonの動作を確認する為のテスト

    /** @test */
    public function CarbonのstartOfWeekの確認()
    {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);
        $date = (new Carbon('2019-7-7'))->startOfWeek();
        $this->assertEquals($date, new Carbon('2019-7-7'));

        $date = (new Carbon('2019-7-8'))->startOfWeek();
        $this->assertEquals($date, new Carbon('2019-7-7'));

        $date = (new Carbon('2019-7-13'))->startOfWeek();
        $this->assertEquals($date, new Carbon('2019-7-7'));
    }
}
