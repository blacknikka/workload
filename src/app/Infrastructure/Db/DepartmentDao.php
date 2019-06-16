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
     * Find a department by name.
     *
     * @param string $name
     * @return Department|null
     */
    public function findByName(string $name) : ?Department
    {
        $queryResult = Db::table(self::DEP_TABLE_NAME)
            ->where(self::DEP_TABLE_NAME . '.name', $name)
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
        // check whether it exists or not.
        if ($this->IsExistByName($department->getName()) === true) {
            // If the same name exists then it is not able to add a new one.
            return 0;
        } else {
            $now = Carbon::now();
            $queryResult = DB::table(self::DEP_TABLE_NAME)
                ->insertGetId([
                    'id' => $department->getId() !== null ? $department->getId() : null,
                    'name' => $department->getName(),
                    'section_name' => $department->getSectionName(),
                    'comment' => $department->getComment(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

            return $queryResult;
        }
    }

    /**
     * check if it exists.
     *
     * @param string $name
     * @return boolean
     */
    public function exists(string $name) : bool
    {
        return $this->IsExistByName($name);
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

    /**
     * Check if it exists.
     *
     * @param string $name
     * @return boolean
     */
    private function IsExistByName(string $name) : bool
    {
        return DB::table(self::DEP_TABLE_NAME)
            ->where(self::DEP_TABLE_NAME . '.name', $name)
            ->exists();
    }
}
