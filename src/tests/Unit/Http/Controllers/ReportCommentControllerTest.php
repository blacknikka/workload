<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Infrastructure\Db\ReportCommentDao;
use App\Infrastructure\Db\UserDao;
use Mockery;
use Illuminate\Support\Collection;
use Tests\Unit\Domain\Report\faker\ReportCommentFaker;
use Tests\Unit\Domain\User\faker\UserFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Carbon\Carbon;
use Illuminate\Http\Response;
use App\Domain\User\User;
use App\Domain\Report\ReportComment;

class ReportCommentControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @var Mockery\MockInterface */
    private $reportCommentDaoMock;

    /** @var Mockery\MockInterface */
    private $userDaoMock;

    public function setUp()
    {
        parent::setUp();

        // 依存をモックする
        $this->reportCommentDaoMock = Mockery::mock(ReportCommentDao::class);
        $this->userDaoMock = Mockery::mock(UserDao::class);

        // 注入
        app()->instance(ReportCommentDao::class, $this->reportCommentDaoMock);
        app()->instance(UserDao::class, $this->userDaoMock);
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
        $response->assertExactJson(
            [
                'result' => false,
                'message' => 'id is not found',
            ]
        );

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
        $response->assertExactJson(
            [
                'result' => true,
                'reportComment' => $reportComment->toArray(),
            ]
        );
        $this->reportCommentDaoMock->shouldHaveReceived('find', [1]);
    }

    /** @test */
    public function createOrUpdateReportComment_userDaoのfindがnull()
    {
        $this->userDaoMock
            ->shouldReceive('find')
            ->andReturn(null);

        $response = $this->postJson(
            route('saveReportComment'),
            [
                'id' => 1,
                'user_id' => 1000,
                'report_comment' => 'str',
                'report_opinion' => 'str',
                'date' => (Carbon::now())->format('Y-m-d'),
            ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson(
            [
                'result' => false,
                'message' => 'user is not found',
            ]
        );
        $this->userDaoMock->shouldHaveReceived('find', [1000]);
    }

    /** @test */
    public function createOrUpdateReportComment_ReportCommentDaoのsaveがnull()
    {
        /** @var User $user */
        $user = UserFaker::create(1)[0];
        $this->userDaoMock
            ->shouldReceive('find')
            ->andReturn($user);

        // saveがnullを返す
        $this->reportCommentDaoMock
            ->shouldReceive('save')
            ->andReturn(null);

        $response = $this->postJson(
            route('saveReportComment'),
            [
                'id' => 1,
                'user_id' => $user->getId(),
                'report_comment' => 'str',
                'report_opinion' => 'str',
                'date' => (Carbon::now())->format('Y-m-d'),
            ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson(
            [
                'result' => false,
                'message' => 'save error',
            ]
        );
        $this->userDaoMock->shouldHaveReceived('find', [$user->getId()]);
        $this->reportCommentDaoMock->shouldHaveReceived('save');
    }

    /** @test */
    public function createOrUpdateReportComment_正常系()
    {
        /** @var User $user */
        $user = UserFaker::create(1)[0];
        $this->userDaoMock
            ->shouldReceive('find')
            ->andReturn($user);

        // saveが正常な値を返す
        $this->reportCommentDaoMock
            ->shouldReceive(
                'save'
            )
            ->andReturn(2);

        $response = $this->postJson(
            route('saveReportComment'),
            [
                'id' => 1,
                'user_id' => $user->getid(),
                'report_comment' => 'str',
                'report_opinion' => 'str',
                'date' => '2019-07-08',
            ]
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson(
            [
                'result' => true,
                'message' => 'The Report Comment has been saved',
            ]
        );
        $this->userDaoMock->shouldHaveReceived('find', [$user->getId()]);
        $this->reportCommentDaoMock->shouldHaveReceived(
            'save'
        )->with(
            \Hamcrest\Matchers::equalTo(
                new ReportComment(
                    1,
                    $user,
                    'str',
                    'str',
                    new Carbon('2019-7-7')      // 2019-7-8の週初めで処理される
                )
            )
        );
    }

    /** @test */
    public function getReportCommentByUserId_findByWeekDayがnullを返す()
    {
        $this->reportCommentDaoMock
            ->shouldReceive('findByWeekDay')
            ->andReturn(null);

        $response = $this->getJson(
            route(
                'getReportCommentByUserId',
                [
                    'id' => 1,
                    'week' => '2019-07-08',
                ]
            )
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson(
            [
                'result' => false,
                'message' => 'Record not found',
            ]
        );
    }

    /** @test */
    public function getReportCommentByUserId_正常系_日付補正()
    {
        /** @var User $user */
        $user = UserFaker::create(1)[0];

        $this->reportCommentDaoMock
            ->shouldReceive('findByWeekDay')
            ->andReturn(
                new ReportComment(
                    1,
                    $user,
                    'str aaa',
                    'str bbb',
                    new Carbon('2019-07-07')
                )
            );

        $response = $this->getJson(
            route(
                'getReportCommentByUserId',
                [
                    'id' => 1,

                    // 7/8を指定しているが、結果として週初めは
                    // 7/7なので、7/7で帰ってくることを期待している
                    'week' => '2019-07-08',
                ]
            )
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson(
            [
                'result' => true,
                'data' => [
                    'id' => 1,
                    'report_comment' => 'str aaa',
                    'report_opinion' => 'str bbb',
                    'date' => '2019-07-07',
                ]
            ]
        );

        $this->reportCommentDaoMock->shouldHaveReceived('findByWeekDay')
            ->with(
                1,
                \Hamcrest\Matchers::equalTo(
                    new Carbon('2019-07-07')
                )
            );
    }
}
