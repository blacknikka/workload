<?php

namespace Tests\Unit\Domain\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use App\Domain\User\QBUserProvider;
use App\Infrastructure\Db\UserDao;

class QBUserProviderTest extends TestCase
{
    use RefreshDatabase;

    /** @var QBUserProvider */
    private $sut;

    /** @var Mockery\MockInterface */
    private $userDaoMock;

    public function setUp()
    {
        parent::setUp();

        // 依存をモックする
        $this->userDaoMock = Mockery::mock(UserDao::class);

        $this->sut = new QBUserProvider(
            $this->userDaoMock
        );

        $this->seed('DepartmentTableSeeder');
        $this->seed('UserTableSeeder');
    }

    public function tearDown()
    {
        parent::tearDown();

        // close
        Mockery::close();
    }

    /** @test */
    public function retrieveById_UserDaoのfindが呼ばれている()
    {
        $this->userDaoMock
            ->shouldReceive('find', [1])
            ->andReturn(null);

        $this->sut
            ->retrieveById(1);

        $this->userDaoMock
            ->shouldHaveReceived('find', [1]);
    }

    /** @test */
    public function retrieveByCredentials_UserDaoのfindが呼ばれている()
    {
        $this->userDaoMock
            ->shouldReceive('find', [1])
            ->andReturn(null);

        $this->sut
            ->retrieveByCredentials([
                'email' => 'test1@example.com',
                'password' => bcrypt('test1'),
            ]);

        $this->userDaoMock
            ->shouldHaveReceived('find', [1]);
    }

}
