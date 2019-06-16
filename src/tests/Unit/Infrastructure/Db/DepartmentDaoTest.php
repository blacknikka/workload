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

    /**
     * @test
     */
    public function findByName_正常系()
    {
        $departmentName = 'dep1';

        $queryResults = Db::table(self::DEP_TABLE_NAME)
            ->where(self::DEP_TABLE_NAME . '.name', $departmentName)
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

        $sutResult = $this->sut->findByName($departmentName);

        // 検証
        $this->assertEquals($sutResult, $objectResults[0]);
        $this->assertCount(1, $objectResults);
    }

    /** @test */
    public function save_正常系()
    {
        // department作成
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

    /** @test */
    public function save_Idあり()
    {
        // department作成
        $department = DepartmentFaker::create(1)[0];

        // save
        $result = $this->sut->save($department);
        $this->assertSame($result, $department->getId());

        // 検証
        $sutResult = $this->sut->find($result);
        $this->assertEquals($sutResult, $department);
    }

    /** @test */
    public function save_同じ名前登録_失敗()
    {
        // department作成
        $department = DepartmentFaker::createWithNullId(1)[0];

        // save
        $result = $this->sut->save($department);
        $this->assertTrue($result > 0);

        // 同じ名前をsave
        $resultWrong = $this->sut->save($department);
        $this->assertSame($resultWrong, 0);

        // DB検証
        $findResult = $this->sut->find($result);
        $tmp = $department->toArray();
        $tmp['id'] = $result;

        $this->assertEquals($findResult->toArray(), $tmp);
    }

    /** @test */
    public function exists_true()
    {
        // department作成
        $department = DepartmentFaker::createWithNullId(1)[0];

        // save
        $result = $this->sut->save($department);
        $this->assertTrue($result > 0);

        $exists = $this->sut->exists($department->getName());
        $this->assertTrue($exists);
    }

    /** @test */
    public function exists_false()
    {
        // department作成
        $department = DepartmentFaker::createWithNullId(1)[0];

        // saveをせずにexists
        $exists = $this->sut->exists($department->getName());
        $this->assertFalse($exists);
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
