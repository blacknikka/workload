<?php
declare(strict_types=1);

namespace App\Infrastructure\Db;

use App\Domain\User\User;
use Carbon\Carbon;
use DB;

/**
 * Class UserDao
 * @package App\Infrastructure\Db
 */
class UserDao
{
    const TABLE_NAME = 'user';

    /**
     * @param int $userId
     * @return User|null
     */
    public function find(int $userId) : ?User
    {
        $queryResult = Db::table(self::TABLE_NAME)->select()->where('id', $userId)->first();

        return is_null($queryResult) ? null : $this->newFromQueryResult($queryResult);
    }

        /**
     * @param \stdClass $queryResult
     * @return User
     */
    private function newFromQueryResult(\stdClass $queryResult) : User
    {
        return new User(
            $queryResult->id,
            $queryResult->email,
            $queryResult->password,
            $queryResult->role,
            $queryResult->activation_token
        );
    }
}
