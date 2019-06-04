<?php

namespace Tests\Unit\Infrastructure\Db;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Domain\User\Department;
use App\Infrastructure\Db\DepartmentDao;
use Illuminate\Support\Collection;
use Tests\Unit\Domain\User\faker\DepartmentFaker;

class DepartmentDaoTest extends TestCase
{
    use RefreshDatabase;

    const DEP_TABLE_NAME = 'department';

    /** @var DepartmentDao */
    private $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = app()->make(DepartmentDao::class);

        $this->artisan('db:seed', ['--class' => 'DepartmentTableSeeder']);
    }

    /**
     * @test
     */
    public function find_正常系()
    {
        // ----------------------------------------
        // 2. アクション
        // ----------------------------------------
        $departmentId = 1;

        $queryResults = Db::table(self::DEP_TABLE_NAME)
            ->where(self::DEP_TABLE_NAME . '.id', $departmentId)
            ->select([
                'id',
                'name',
                'section_name',
                'comment',
                'created_at',
                'updated_at',
            ])
            ->get();
        $objectResults = $this->newFromQueryResults($queryResults);

        $sutResult = $this->sut->find($departmentId);


        // ----------------------------------------
        // 3. 検証
        // ----------------------------------------
        $this->assertEquals($sutResult, $objectResults[0]);
        $this->assertCount(1, $objectResults);
    }

    /** @test */
    public function find_存在しないidを検索すると、nullが返却される()
    {
        // ----------------------------------------
        // 2. アクション
        // ----------------------------------------
        $result = $this->sut->find(999);

        // ----------------------------------------
        // 3. 検証
        // ----------------------------------------
        $this->assertNull($result);
        $this->assertDatabaseMissing(
            DepartmentDao::DEP_TABLE_NAME,
            ['id' => 999]
        );
    }

    /** @test */
    public function save_正常系()
    {
        // user作成
        $department = DepartmentFaker::createWithNullId(1)[0];

        // save
        $result = $this->sut->save($department);
        $this->assertTrue($result > 0);

        // 検証
        $sutResult = $this->sut->find($result);
        $tmp = $department->toArray();
        $tmp['id'] = $result;

        $this->assertEquals($sutResult->toArray(), $tmp);
    }

    /**
     * @param array $queryResults
     * @return array
     */
    private function newFromQueryResults(Collection $queryResults) : array
    {
        $department = [];
        foreach ($queryResults as $queryResult) {
            $department[] =
            new Department(
                $queryResult->id,
                $queryResult->name,
                $queryResult->section_name,
                $queryResult->comment
            );
        }
        return $department;
    }
}
