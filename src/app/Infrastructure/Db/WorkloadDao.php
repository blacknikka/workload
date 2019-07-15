<?php
declare(strict_types=1);

namespace App\Infrastructure\Db;

use App\Domain\Workload\Workload;
use App\Domain\Workload\Project;
use App\Domain\Workload\Category;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

/**
 * Class WorkloadDao
 * @package App\Infrastructure\Db
 */
class WorkloadDao
{
    const PROJECT_TABLE_NAME = 'project';
    const CATEGORY_TABLE_NAME = 'category';
    const WORKLOAD_TABLE_NAME = 'workload';

    public function find(int $wId) : ?Workload
    {
        $queryResult = Db::table(self::WORKLOAD_TABLE_NAME)
            ->where(self::WORKLOAD_TABLE_NAME . '.id', $wId)
            ->select([
                self::WORKLOAD_TABLE_NAME . '.id as workloadId',
                self::WORKLOAD_TABLE_NAME . '.user_id as userId',
                self::WORKLOAD_TABLE_NAME . '.project_id as projId',
                self::WORKLOAD_TABLE_NAME . '.category_id as catId',
                'amount',
                'date'
            ])
            ->first();

        return is_null($queryResult) ? null : $this->newFromQueryResult($queryResult);
    }

    /**
     * UserIdにより検索
     *
     * @param integer $userId
     * @return Workload[]
     */
    public function findByUserId(int $userId) : Collection
    {
        $queryResult = Db::table(self::WORKLOAD_TABLE_NAME)
            ->where(self::WORKLOAD_TABLE_NAME . '.user_id', $userId)
            ->select([
                self::WORKLOAD_TABLE_NAME . '.id as workloadId',
                self::WORKLOAD_TABLE_NAME . '.user_id as userId',
                self::WORKLOAD_TABLE_NAME . '.project_id as projId',
                self::WORKLOAD_TABLE_NAME . '.category_id as catId',
                'amount',
                'date'
            ])
            ->get();

        $results = collect($queryResult)->map(function ($q) {
            return $this->newFromQueryResult($q);
        });

        return $results;
    }

    /**
     * UserId, 日付情報から検索
     *
     * @param integer $userId
     * @param Carbon $month
     * @return Workload[]
     */
    public function findByMonth(int $userId, Carbon $month) : Collection
    {
        $queryResult = DB::table(self::WORKLOAD_TABLE_NAME)
            ->where(self::WORKLOAD_TABLE_NAME . '.user_id', $userId)
            ->whereYear(self::WORKLOAD_TABLE_NAME . '.date', '=', $month->year)
            ->whereMonth(self::WORKLOAD_TABLE_NAME . '.date', '=', $month->month)
            ->select(
                [
                    self::WORKLOAD_TABLE_NAME . '.id as workloadId',
                    self::WORKLOAD_TABLE_NAME . '.user_id as userId',
                    self::WORKLOAD_TABLE_NAME . '.project_id as projId',
                    self::WORKLOAD_TABLE_NAME . '.category_id as catId',
                    'amount',
                    'date'
                ]
            )
            ->get();

        $results = collect($queryResult)
            ->map(
                function ($q) {
                    return $this->newFromQueryResult($q);
                }
            );

        return $results;
    }

    /**
     * @param Workload $workload
     * @return int ID
     */
    public function save(Workload $workload) : int
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        $workloadArrayForSave = [
            'id'    => $workload->getId(),
            'user_id' => $workload->getUserId(),
            'project_id' => $workload->getProjectId(),
            'category_id' => $workload->getCategoryId(),
            'amount' => $workload->getAmount(),
            'date' => $workload->getDate(),
        ];

        // 登録の確認
        $project = DB::table(self::PROJECT_TABLE_NAME)
            ->where('id', $workload->getProjectId())
            ->exists();

        $category = DB::table(self::CATEGORY_TABLE_NAME)
            ->where('id', $workload->getCategoryId())
            ->exists();

        $id = -1;
        if ($project === true && $category === true) {
            if ($workload->getId() === null) {
                $workloadArrayForSave['created_at'] = $now;
                $id = DB::table(self::WORKLOAD_TABLE_NAME)
                    ->insertGetId($workloadArrayForSave);
            } else {
                $id = $workload->getId();
                DB::table(self::WORKLOAD_TABLE_NAME)
                    ->where('id', $id)
                    ->update($workloadArrayForSave);
            }
        }

        return $id;
    }

    /**
     * 複数のデータを更新する処理
     *
     * @param Workload[] $workloads
     * @return array
     */
    public function updateSeveralData(Collection $workloads) : array
    {
        $result = DB::transaction(
            function () use ($workloads) {
                $saveResult = $workloads->map(
                    function ($workload) {
                        return $this->save($workload);
                    }
                );

                return [
                    'result' => true,
                    'saveResult' => $saveResult,
                ];
            }
        );

        return $result;
    }

    /**
     * Projectのリストを取得
     *
     * @return Project[]
     */
    public function getProjectList() : Collection
    {
        $projectList = DB::table(self::PROJECT_TABLE_NAME)
            ->select(
                [
                    'id',
                    'name',
                    'comment',
                ]
            )
            ->get();

        return collect($projectList)->map(
            function ($project) {
                return $this->newProjectFromQueryResult($project);
            }
        );
    }

    /**
     * Categoryのリストを取得
     *
     * @return Collection
     */
    public function getCategoryList() : Collection
    {
        $categoryList = DB::table(self::CATEGORY_TABLE_NAME)
            ->select(
                [
                    'id',
                    'name',
                    'comment',
                ]
            )
            ->get();

        return collect($categoryList)->map(
            function ($category) {
                return $this->newCategoryFromQueryResult($category);
            }
        );
    }

    /**
     * @param \stdClass $queryResult
     * @return Workload
     */
    private function newFromQueryResult(\stdClass $queryResult) : Workload
    {
        return new Workload(
            $queryResult->workloadId,
            $queryResult->userId,
            $queryResult->projId,
            $queryResult->catId,
            (float)$queryResult->amount,
            new Carbon($queryResult->date)
        );
    }

    /**
     * Projectを取得する
     *
     * @param \stdClass $queryResult
     * @return Project
     */
    private function newProjectFromQueryResult(\stdClass $queryResult) : Project
    {
        return new Project(
            $queryResult->id,
            $queryResult->name,
            $queryResult->comment
        );
    }

    /**
     * Categoryを取得する
     *
     * @param \stdClass $queryResult
     * @return Category
     */
    private function newCategoryFromQueryResult(\stdClass $queryResult) : Category
    {
        return new Category(
            $queryResult->id,
            $queryResult->name,
            $queryResult->comment
        );
    }
}
