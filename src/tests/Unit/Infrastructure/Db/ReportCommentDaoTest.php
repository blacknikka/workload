<?php

namespace Tests\Unit\Infrastructure\Db;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\User\User;
use App\Domain\Report\ReportComment;
use App\Infrastructure\Db\ReportCommentDao;
use App\Infrastructure\Db\UserDao;
use Illuminate\Support\Collection;
use Tests\Unit\Domain\Report\faker\ReportCommentFaker;
use Tests\Unit\Domain\User\faker\UserFaker;
use Mockery;
use Carbon\Carbon;
use DB;


class ReportCommentDaoTest extends TestCase
{
    use RefreshDatabase;

    const REPORT_TABLE_NAME = 'report';

    /** @var ReportCommentDao */
    private $sut;

    /** @var Mockery\MockInterface */
    private $userDaoMock;

    /**
     * レコードの数を数える
     *
     * @return integer
     */
    private function fetchCurrentRecordCount() : int
    {
        return DB::table(self::REPORT_TABLE_NAME)->count();
    }

    public function setUp()
    {
        parent::setUp();

        // ---------------------
        // ReportCommentDaoを作る前にMockを作る
        $this->userDaoMock = Mockery::mock(UserDao::class);
        app()->instance(UserDao::class, $this->userDaoMock);

        // MockされたUserDaoをもつReportCommentDaoを設定
        $this->sut = app()->make(ReportCommentDao::class);

        $this->artisan('db:seed', ['--class' => 'UserTableSeeder']);
        $this->artisan('db:seed', ['--class' => 'DepartmentTableSeeder']);
    }

    /** @test */
    public function mockのテスト()
    {
        $user = UserFaker::create(1)[0];
        $this->userDaoMock
            ->shouldReceive('find')
            ->andReturn($user);

        $userDao = app()->make(UserDao::class);
        $userResult = $userDao->find($user->getId());
        $this->assertEquals($userResult, $user);

        $this->userDaoMock->shouldHaveReceived('find');
    }

    /** @test */
    public function find_正常系()
    {
        /**
         * @var ReportComment $reportComment
         */
        $reportComment = ReportCommentFaker::createWithNullId(1)[0];
        $savedId = $this->sut->save($reportComment);
        $this->assertNotNull($savedId);

        $savedReportComment = new ReportComment(
            $savedId,
            $reportComment->getUser(),
            $reportComment->getReportComment(),
            $reportComment->getReportOpinion(),
            $reportComment->getDate()
        );

        // UserDaoのアクセスにはmockを使う
        $this->userDaoMock
            ->shouldReceive('find')
            ->andReturn($reportComment->getUser());

        $foundResult = $this->sut->find($savedId);
        $this->userDaoMock->shouldHaveReceived('find');
        $this->assertNotNull($foundResult);
        $this->assertEquals($savedReportComment, $foundResult);
    }

    /** @test */
    public function findByWeekDay_正常系()
    {
        $reportComment = ReportCommentFaker::createWithNullId(1)[0];

        $savedId = $this->sut->save($reportComment);
        $this->assertNotNull($savedId > 0);

        $savedReportComment = new ReportComment(
            $savedId,
            $reportComment->getUser(),
            $reportComment->getReportComment(),
            $reportComment->getReportOpinion(),
            $reportComment->getDate()
        );

        // UserDaoのアクセスにはmockを使う
        $this->userDaoMock
            ->shouldReceive('find')
            ->andReturn($savedReportComment->getUser());

        $foundResult = $this->sut->findByWeekDay(
            $savedReportComment->getUser()->getId(),
            $reportComment->getDate()
        );
        $this->userDaoMock->shouldHaveReceived('find');
        $this->assertNotNull($foundResult);
        $this->assertEquals(
            $savedReportComment,
            $foundResult
        );
    }

    /** @test */
    public function findByWeekDay_ReportCommentが見つからない()
    {
        $foundResult = $this->sut->findByWeekDay(
            1000,
            Carbon::now()
        );

        $this->assertNull($foundResult);
    }

    /** @test */
    public function findByWeekDay_Userが見つからない()
    {
        $reportComment = ReportCommentFaker::createWithNullId(1)[0];

        $savedId = $this->sut->save($reportComment);
        $this->assertNotNull($savedId > 0);

        $savedReportComment = new ReportComment(
            $savedId,
            $reportComment->getUser(),
            $reportComment->getReportComment(),
            $reportComment->getReportOpinion(),
            $reportComment->getDate()
        );

        // UserDaoのアクセスにはmockを使う
        // UserDaoがnullを返してくる
        $this->userDaoMock
            ->shouldReceive('find')
            ->andReturn(null);

        $foundResult = $this->sut->findByWeekDay(
            $savedReportComment->getUser()->getId(),
            $reportComment->getDate()
        );
        $this->userDaoMock->shouldHaveReceived('find');
        $this->assertNull($foundResult);
    }

    /** @test */
    public function save_isnert_正常系()
    {
        $recordCount = $this->fetchCurrentRecordCount();

        /**
         * @var ReportComment $reportComment
         */
        $reportComment = ReportCommentFaker::createWithNullId(1)[0];

        $savedId = $this->sut->save($reportComment);
        $this->assertDatabaseHas(
            self::REPORT_TABLE_NAME,
            [
                'id' => $savedId,
                'user_id' => $reportComment->getUser()->getId(),
                'report_comment' => $reportComment->getReportComment(),
                'report_opinion' => $reportComment->getReportOpinion(),
                'date' => $reportComment->getDate(),
            ]
        );

        $this->assertSame(
            $recordCount + 1,
            $this->fetchCurrentRecordCount()
        );
    }

    /** @test */
    public function save_update_正常系()
    {
        /**
         * @var ReportComment $reportComment
         */
        $reportComment = ReportCommentFaker::createWithNullId(1)[0];

        $this->assertNull($reportComment->getId());
        $savedId = $this->sut->save($reportComment);
        $this->assertDatabaseHas(
            self::REPORT_TABLE_NAME,
            [
                'id' => $savedId,
                'user_id' => $reportComment->getUser()->getId(),
                'report_comment' => $reportComment->getReportComment(),
                'report_opinion' => $reportComment->getReportOpinion(),
                'date' => $reportComment->getDate(),
            ]
        );

        $recordCntBeforeUpdate = $this->fetchCurrentRecordCount();

        // updateする対象を作る
        $updateTarget = new ReportComment(
            $savedId,
            $reportComment->getUser(),
            'sample string',
            'my opinion',
            $reportComment->getDate()
        );

        // update
        $updateResult = $this->sut->save($updateTarget);
        $this->assertTrue($updateResult > 0);
        $this->assertDatabaseHas(
            self::REPORT_TABLE_NAME,
            [
                'id' => $savedId,
                'user_id' => $updateTarget->getUser()->getId(),
                'report_comment' => $updateTarget->getReportComment(),
                'report_opinion' => $updateTarget->getReportOpinion(),
                'date' => $updateTarget->getDate()->format('Y-m-d'),
            ]
        );

        $this->assertSame(
            $recordCntBeforeUpdate,
            $this->fetchCurrentRecordCount()
        );
    }
}
