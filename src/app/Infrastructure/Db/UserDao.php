<?php
declare(strict_types=1);

namespace App\Infrastructure\Db;

use App\Domain\User\User;
use App\Domain\User\Department;
use Carbon\Carbon;
use DB;

/**
 * Class UserDao
 * @package App\Infrastructure\Db
 */
class UserDao
{
    const USER_TABLE_NAME = 'user';
    const DEP_TABLE_NAME = 'department';

    /**
     * @param int $userId
     * @return User|null
     */
    public function find(int $userId) : ?User
    {
        $queryResult = Db::table(self::USER_TABLE_NAME)
            ->select()
            ->join(self::DEP_TABLE_NAME, self::USER_TABLE_NAME . '.depId', '=', self::DEP_TABLE_NAME . '.id')
            ->where(self::USER_TABLE_NAME . '.id', $userId)
            ->select([
                self::USER_TABLE_NAME . '.id as userId',
                self::USER_TABLE_NAME . '.name as userName',
                'email',
                'password',
                'role',
                'activation_token',
                self::DEP_TABLE_NAME . '.id as depId',
                self::DEP_TABLE_NAME . '.name as depName',
                self::DEP_TABLE_NAME . '.section_name as depSecName',
                self::DEP_TABLE_NAME . '.comment as depComment',
            ])
            ->first();

        return is_null($queryResult) ? null : $this->newFromQueryResult($queryResult);
    }

    /**
     * @param \stdClass $queryResult
     * @return User
     */
    private function newFromQueryResult(\stdClass $queryResult) : User
    {
        return new User(
            $queryResult->userId,
            $queryResult->userName,
            new Department(
                $queryResult->depId,
                $queryResult->depName,
                $queryResult->depSecName,
                $queryResult->depComment
            ),
            $queryResult->email,
            $queryResult->password,
            $queryResult->role,
            $queryResult->activation_token
        );
    }
}
