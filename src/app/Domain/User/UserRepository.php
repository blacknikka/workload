<?php
declare(strict_types=1);

namespace App\Domain\User;

use App\Infrastructure\Db\UserDao;

/**
 * Class UserRepository
 * @package App\Domain\User
 */
class UserRepository
{
    /** @var UserDao */
    private $userDao;

    /**
     * UserRepository constructor.
     * @param UserDao $UserDao
     */
    public function __construct(UserDao $UserDao)
    {
        $this->userDao = $UserDao;
    }

    /**
     * @param User $user
     * @return int|null
     */
    public function save(User $user) : ?int
    {
        return $this->userDao->save($user);
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function findById(int $id) : ?User
    {
        return $this->userDao->find($id);
    }

    /**
     * EmailからUserを検索する
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email) : ?User
    {
        return $this->userDao->findByEmail($email);
    }
}
