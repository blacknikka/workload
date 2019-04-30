<?php
declare(strict_types=1);

namespace App\Infrastructure\Db;

use App\Domain\Workload\Workload;
use App\Domain\Workload\Project;
use App\Domain\Workload\Category;
use Carbon\Carbon;
use DB;

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
            ->join(
                self::PROJECT_TABLE_NAME,
                self::WORKLOAD_TABLE_NAME . '.project_id',
                '=',
                self::PROJECT_TABLE_NAME . '.id'
            )
            ->join(
                self::CATEGORY_TABLE_NAME,
                self::WORKLOAD_TABLE_NAME . '.category_id',
                '=',
                self::CATEGORY_TABLE_NAME . '.id'
            )
            ->where(self::WORKLOAD_TABLE_NAME . '.id', $wId)
            ->select([
                self::WORKLOAD_TABLE_NAME . '.id as workloadId',
                self::PROJECT_TABLE_NAME . '.id as projId',
                self::PROJECT_TABLE_NAME . '.name as projName',
                self::PROJECT_TABLE_NAME . '.comment as projComment',
                self::CATEGORY_TABLE_NAME . '.id as catId',
                self::CATEGORY_TABLE_NAME . '.name as catName',
                self::CATEGORY_TABLE_NAME . '.comment as catComment',
                'amount',
                'date'
            ])
            ->first();

        return is_null($queryResult) ? null : $this->newFromQueryResult($queryResult);
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
            'project_id' => $workload->getProject()->getId(),
            'category_id' => $workload->getCategory()->getId(),
            'amount' => $workload->getAmount(),
            'date' => $workload->getDate(),
        ];

        //TODO: transaction
        // workloadに登録
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

        return $id;
    }

    /**
     * @param \stdClass $queryResult
     * @return Workload
     */
    private function newFromQueryResult(\stdClass $queryResult) : Workload
    {
        return new Workload(
            $queryResult->workloadId,
            new Project(
                $queryResult->projId,
                $queryResult->projName,
                $queryResult->projComment
            ),
            new Category(
                $queryResult->catId,
                $queryResult->catName,
                $queryResult->catComment
            ),
            (float)$queryResult->amount,
            new Carbon($queryResult->date)
        );
    }
}
