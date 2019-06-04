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
            ->where(self::USER_TABLE_NAME . '.id', $userId)
            ->join(self::DEP_TABLE_NAME, self::USER_TABLE_NAME . '.depId', '=', self::DEP_TABLE_NAME . '.id')
            ->select([
                self::USER_TABLE_NAME . '.id as userId',
                self::USER_TABLE_NAME . '.name as userName',
                'email',
                'password',
                'role',
                self::DEP_TABLE_NAME . '.id as depId',
                self::DEP_TABLE_NAME . '.name as depName',
                self::DEP_TABLE_NAME . '.section_name as depSecName',
                self::DEP_TABLE_NAME . '.comment as depComment',
            ])
            ->first();

        return is_null($queryResult) ? null : $this->newFromQueryResult($queryResult);
    }

    /**
     * save to DB
     *
     * @param User $user
     * @return int
     */
    public function save(User $user) : int
    {
        $now = Carbon::now();
        $queryResult = DB::table(self::USER_TABLE_NAME)
            ->insertGetId([
                'id' => null,
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'role' => $user->getRole(),
                'depId' => $user->getDepartment()->getId(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

        return $queryResult;
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
            $queryResult->role
        );
    }
}
