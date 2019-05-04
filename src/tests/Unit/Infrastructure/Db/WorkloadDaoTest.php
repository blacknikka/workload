<?php

namespace Tests\Unit\Infrastructure\Db;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Domain\Workload\Workload;
use App\Domain\Workload\Category;
use App\Domain\Workload\Project;
use App\Infrastructure\Db\WorkloadDao;
use Illuminate\Support\Collection;
use Tests\Unit\Domain\Workload\faker\WorkloadFaker;
use Faker\Generator as Faker;

class WorkloadDaoTest extends TestCase
{
    use RefreshDatabase;

    const PROJECT_TABLE_NAME = 'project';
    const CATEGORY_TABLE_NAME = 'category';
    const WORKLOAD_TABLE_NAME = 'workload';

    /** @var WorkloadDao */
    private $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = app()->make(WorkloadDao::class);
    }

    /**
     * Workloadのデータを挿入する
     *
     * @param Workload $workload
     * @return int
     */
    private function insertWorkloadData(Workload $workload) : int
    {
        $workloadArrayForSave = [
            'id'    => $workload->getId(),
            'user_id' => $workload->getUserId(),
            'project_id' => $workload->getProjectId(),
            'category_id' => $workload->getCategoryId(),
            'amount' => $workload->getAmount(),
            'date' => $workload->getDate(),
        ];

        $id = DB::table(self::WORKLOAD_TABLE_NAME)
            ->insertGetId($workloadArrayForSave);

        // ProjectとCategoryの登録
        $this->saveToProject($workload->getProjectId());
        $this->saveToCategory($workload->getCategoryId());

        return $id;
    }

    /**
     * Projectの登録
     *
     * @param integer $projectId
     * @return void
     */
    private function saveToProject(int $projectId) : void
    {
        $faker = app()->make(Faker::class);

        DB::table(self::PROJECT_TABLE_NAME)
            ->insert([
                'id' => $projectId,
                'name' => $faker->word(),
                'comment' => $faker->sentence(),
            ]);
    }

    /**
     * Categoryの登録
     *
     * @param integer $categoryId
     * @return void
     */
    private function saveToCategory(int $categoryId) : void
    {
        $faker = app()->make(Faker::class);

        DB::table(self::CATEGORY_TABLE_NAME)
            ->insert([
                'id' => $categoryId,
                'name' => $faker->word(),
                'comment' => $faker->sentence(),
            ]);
    }

    /** @test */
    public function find_正常系()
    {
        $data = WorkloadFaker::createWithNullId(1)[0];
        $id = $this->insertWorkloadData($data);

        $result = $this->sut->find($id);

        // 検証
        $this->assertNotNull($result);
        $this->assertNotNull($result->getId());
        $this->assertEquals($result->getUserId(), $data->getUserId());
        $this->assertEquals($result->getProjectId(), $data->getProjectId());
        $this->assertEquals($result->getCategoryId(), $data->getCategoryId());
        $this->assertEquals($result->getAmount(), $data->getAmount());
        $this->assertEquals($result->getDate(), $data->getDate());
    }

    /** @test */
    public function save_正常系_nullID()
    {
        // テスト用のデータを作成
        $data = WorkloadFaker::createWithNullId(1)[0];
        $this->assertNull($data->getId());

        $this->saveToProject($data->getProjectId());
        $this->saveToCategory($data->getCategoryId());

        // データをDBに保存
        $sutResult = $this->sut->save($data);

        // データを読み出し
        $readData = $this->sut->find($sutResult);

        // 検証
        $this->assertNotNull($readData);
        $this->assertNotNull($readData->getId());
        $this->assertEquals($readData->getUserId(), $data->getUserId());
        $this->assertEquals($readData->getProjectId(), $data->getProjectId());
        $this->assertEquals($readData->getCategoryId(), $data->getCategoryId());
        $this->assertEquals($readData->getAmount(), $data->getAmount());
        $this->assertEquals($readData->getDate(), $data->getDate());
    }

    /** @test */
    public function save_異常系_projectなし()
    {
        $data = WorkloadFaker::createWithNullId(1)[0];
        $this->assertNull($data->getId());

        // categoryのみ登録
        // $this->saveToProject($data->getProjectId());
        $this->saveToCategory($data->getCategoryId());

        // DBに登録する
        $sutResult = $this->sut->save($data);

        $this->assertSame($sutResult, -1);
    }

    /** @test */
    public function save_異常系_categoryなし()
    {
        $data = WorkloadFaker::createWithNullId(1)[0];
        $this->assertNull($data->getId());

        // projectのみ登録
        $this->saveToProject($data->getProjectId());
        // $this->saveToCategory($data->getCategoryId());

        // DBに登録する
        $sutResult = $this->sut->save($data);

        $this->assertSame($sutResult, -1);
    }
}
