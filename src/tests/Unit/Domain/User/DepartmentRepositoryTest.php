<?php

namespace Tests\Unit\Domain\User;

use App\Domain\User\DepartmentRepository;
use App\Infrastructure\Db\DepartmentDao;
use Mockery;
use Tests\Unit\Domain\User\faker\DepartmentFaker;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepartmentRepositoryTest extends TestCase
{
    /** @var DepartmentRepository */
    private $sut;

    /** @var Mockery\MockInterface */
    private $departmentMock;

    public function setUp()
    {
        parent::setUp();

        // 依存をモックする
        $this->departmentMock = Mockery::mock(DepartmentDao::class);

        // 注入
        app()->instance(DepartmentDao::class, $this->departmentMock);

        $this->sut = app()->make(DepartmentRepository::class);
    }

    public function tearDown()
    {
        parent::tearDown();

        // close
        Mockery::close();
    }

    /** @test */
    public function findById_DepartmentDaoのfindが呼ばれている()
    {
        $this->departmentMock
            ->shouldReceive('find')
            ->andReturn(null);

        $this->sut->findById(1);

        $this->departmentMock
            ->shouldHaveReceived('find', [1]);
    }

    /** @test */
    public function save_DepartmentDaoのsaveが呼ばれている()
    {
        $users = DepartmentFaker::create(1);
        $this->departmentMock
            ->shouldReceive('save')
            ->andReturn(1);

        $result = $this->sut->save($users[0]);

        $this->departmentMock
            ->shouldHaveReceived('save', [$users[0]]);
        $this->assertSame($result, 1);
    }

    /** @test */
    public function exists_DepartmentDaoのexistsが呼ばれている()
    {
        $this->departmentMock
            ->shouldReceive('exists')
            ->andReturn(true);

        $result = $this->sut->exists('namae');

        $this->departmentMock
            ->shouldHaveReceived('exists', ['namae']);
        $this->assertTrue($result);
    }

    /** @test */
    public function existsById_DepartmentDaoのexistsByIdが呼ばれている()
    {
        $this->departmentMock
            ->shouldReceive('existsById')
            ->andReturn(true);

        $result = $this->sut->existsById(1);

        $this->departmentMock
            ->shouldHaveReceived('existsById', [1]);
        $this->assertTrue($result);
    }
}
