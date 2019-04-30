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
            'project_id' => $workload->getProject()->getId(),
            'category_id' => $workload->getCategory()->getId(),
            'amount' => $workload->getAmount(),
            'date' => $workload->getDate(),
        ];

        $id = DB::table(self::WORKLOAD_TABLE_NAME)
            ->insertGetId($workloadArrayForSave);

        $projectArrayForSave = [
            'id'    => $workload->getProject()->getId(),
            'name' => $workload->getProject()->getName(),
            'comment' => $workload->getProject()->getComment(),
        ];

        DB::table(self::PROJECT_TABLE_NAME)
            ->insertGetId($projectArrayForSave);

        $categoryArrayForSave = [
            'id'    => $workload->getCategory()->getId(),
            'name' => $workload->getCategory()->getName(),
            'comment' => $workload->getCategory()->getComment(),
        ];

        DB::table(self::CATEGORY_TABLE_NAME)
            ->insertGetId($categoryArrayForSave);

        return $id;
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
        $this->assertEquals($result->getProject(), $data->getProject());
        $this->assertEquals($result->getCategory(), $data->getCategory());
        $this->assertEquals($result->getAmount(), $data->getAmount());
        $this->assertEquals($result->getDate(), $data->getDate());
    }

    /** @test */
    public function save_正常系_nullID()
    {
        // テスト用のデータを作成
        $data = WorkloadFaker::createWithNullId(1)[0];
        $this->assertNull($data->getId());

        // データをDBに保存
        $sutResult = $this->sut->save($data);

        // データを読み出し
        $readData = $this->sut->find($sutResult);

        // 検証
        $this->assertNotNull($readData);
        $this->assertNotNull($readData->getId());
        $this->assertEquals($readData->getProject(), $data->getProject());
        $this->assertEquals($readData->getCategory(), $data->getCategory());
        $this->assertEquals($readData->getAmount(), $data->getAmount());
        $this->assertEquals($readData->getDate(), $data->getDate());
    }
}
