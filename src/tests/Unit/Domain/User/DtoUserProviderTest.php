<?php

namespace Tests\Unit\Domain\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use App\Domain\User\DtoUserProvider;
use App\Infrastructure\Db\UserDao;

class DtoUserProviderTest extends TestCase
{
    /** @var DtoUserProvider */
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

        $this->sut = app()->make(DtoUserProvider::class);
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
            ->shouldReceive('find')
            ->andReturn(null);

        $this->sut
            ->retrieveByCredentials([
                'email' => 'test1@example.com',
                'password' => bcrypt('test1'),
            ]);

        $this->userDaoMock
            ->shouldHaveReceived('find');
    }

}
