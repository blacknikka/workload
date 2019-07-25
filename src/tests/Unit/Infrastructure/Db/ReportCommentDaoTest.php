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
use DB;


class ReportCommentDaoTest extends TestCase
{
    use RefreshDatabase;

    const REPORT_TABLE_NAME = 'report';

    /** @var ReportCommentDao */
    private $sut;

    /** @var Mockery\MockInterface */
    private $userDaoMock;

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
            $reportComment->getReportOpinion()
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
    public function save_正常系()
    {
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
            ]
        );
    }

    /** @test */
    public function update_id_null()
    {
        /**
         * @var ReportComment $reportComment
         */
        $reportComment = ReportCommentFaker::createWithNullId(1)[0];

        $updateResult = $this->sut->update($reportComment);
        $this->assertFalse($updateResult);
    }

    /** @test */
    public function update_正常系()
    {
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
            ]
        );

        // updateする対象を作る
        $updateTarget = new ReportComment(
            $savedId,
            $reportComment->getUser(),
            'sample string',
            'my opinion'
        );

        // update
        $updateResult = $this->sut->update($updateTarget);
        $this->assertTrue($updateResult);
        $this->assertDatabaseHas(
            self::REPORT_TABLE_NAME,
            [
                'id' => $savedId,
                'user_id' => $updateTarget->getUser()->getId(),
                'report_comment' => $updateTarget->getReportComment(),
                'report_opinion' => $updateTarget->getReportOpinion(),
            ]
        );
    }

    /** @test */
    public function update_失敗_IDなし()
    {
        /**
         * @var ReportComment $reportComment
         */
        $reportComment = ReportCommentFaker::create(1)[0];

        // saveする前にupdate
        $updateResult = $this->sut->update($reportComment);
        $this->assertFalse($updateResult);
    }

}
