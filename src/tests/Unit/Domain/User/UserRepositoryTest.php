<?php

namespace Tests\Unit\Domain\User;

use App\Domain\User\UserRepository;
use App\Infrastructure\Db\UserDao;
use Mockery;
use Tests\Unit\Domain\User\faker\UserFaker;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRepositoryTest extends TestCase
{
    /** @var UserRepository */
    private $sut;
    /** @var Mockery\MockInterface */
    private $userDaoMock;

    public function setUp()
    {
        parent::setUp();

        // 依存をモックする
        $this->userDaoMock = Mockery::mock(UserDao::class);

        // 注入
        app()->instance(UserDao::class, $this->userDaoMock);

        $this->sut = app()->make(UserRepository::class);
    }

    public function tearDown()
    {
        parent::tearDown();

        // close
        Mockery::close();
    }

    /** @test */
    public function find_UserDaoのfindが呼ばれている()
    {
        $this->userDaoMock
            ->shouldReceive('find')
            ->andReturn(null);

        $this->sut->findById(1);

        $this->userDaoMock
            ->shouldHaveReceived('find', [1]);
    }

    /** @test */
    public function save_UserDaoのsaveが呼ばれている()
    {
        $users = UserFaker::create(1);
        $this->userDaoMock
            ->shouldReceive('save')
            ->andReturn(1);

        $result = $this->sut->save($users[0]);

        $this->userDaoMock
            ->shouldHaveReceived('save', [$users[0]]);
        $this->assertSame($result, 1);
    }
}
