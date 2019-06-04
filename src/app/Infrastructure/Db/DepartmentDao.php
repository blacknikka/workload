<?php
declare(strict_types=1);

namespace App\Infrastructure\Db;

use App\Domain\User\Department;
use Carbon\Carbon;
use DB;

/**
 * Class DepartmentDao
 * @package App\Infrastructure\Db
 */
class DepartmentDao
{
    const DEP_TABLE_NAME = 'department';

    /**
     * @param int $userId
     * @return Department|null
     */
    public function find(int $departmentId) : ?Department
    {
        $queryResult = Db::table(self::DEP_TABLE_NAME)
            ->where(self::DEP_TABLE_NAME . '.id', $departmentId)
            ->select([
                'id',
                'name',
                'section_name',
                'comment',
                'created_at',
                'updated_at',
            ])
            ->first();

        return is_null($queryResult) ? null : $this->newFromQueryResult($queryResult);
    }

    /**
     * save to DB
     *
     * @param Department $user
     * @return int
     */
    public function save(Department $department) : int
    {
        $now = Carbon::now();
        $queryResult = DB::table(self::DEP_TABLE_NAME)
            ->insertGetId([
                'id' => null,
                'name' => $department->getName(),
                'section_name' => $department->getSectionName(),
                'comment' => $department->getComment(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

        return $queryResult;
    }

    /**
     * @param \stdClass $queryResult
     * @return Department
     */
    private function newFromQueryResult(\stdClass $queryResult) : Department
    {
        return new Department(
            $queryResult->id,
            $queryResult->name,
            $queryResult->section_name,
            $queryResult->comment
        );
    }
}
