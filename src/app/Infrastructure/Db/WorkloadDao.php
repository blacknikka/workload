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
            ->where(self::WORKLOAD_TABLE_NAME . '.id', $wId)
            ->select([
                self::WORKLOAD_TABLE_NAME . '.id as workloadId',
                self::WORKLOAD_TABLE_NAME . '.project_id as projId',
                self::WORKLOAD_TABLE_NAME . '.category_id as catId',
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
            'project_id' => $workload->getProjectId(),
            'category_id' => $workload->getCategoryId(),
            'amount' => $workload->getAmount(),
            'date' => $workload->getDate(),
        ];

        // workloadに登録
        $project = DB::table(self::PROJECT_TABLE_NAME)
            ->exists('id', $workload->getProjectId());

        $category = DB::table(self::CATEGORY_TABLE_NAME)
            ->exists('id', $workload->getCategoryId());

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
     * @param \stdClass $queryResult
     * @return Workload
     */
    private function newFromQueryResult(\stdClass $queryResult) : Workload
    {
        return new Workload(
            $queryResult->workloadId,
            $queryResult->projId,
            $queryResult->catId,
            (float)$queryResult->amount,
            new Carbon($queryResult->date)
        );
    }
}
