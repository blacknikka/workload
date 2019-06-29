<?php
declare(strict_types=1);

namespace App\Domain\User;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Auth\DatabaseUserProvider;
use Illuminate\Auth\GenericUser;
use App\Infrastructure\Db\UserDao;
use App\Domain\User\User;

class DtoUserProvider extends DatabaseUserProvider implements UserProvider
{
    /** @var UserDao */
    private $userDao;

    public function __construct(UserDao $userDao)
    {
        parent::__construct(
            app()->make('db')->connection(),
            app()->make('hash'),
            UserDao::USER_TABLE_NAME
        );

        $this->userDao = $userDao;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @override
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return $this->userDao->find($identifier);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @override
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        /** @var GenericUser|null */
        $genericUser = parent::retrieveByCredentials($credentials);
        return is_null($genericUser) ? null : $this->userDao->find($genericUser->getAuthIdentifier());
    }
}
