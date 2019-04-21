<?php
declare(strict_types=1);

namespace App\Domain\User;

use Illuminate\Notifications\Notifiable;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * Class User
 * @package App\Domain\User
 */
class User implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use Notifiable;

    public $id;
    private $email;
    private $password;
    private $role;
    private $activationToken;

    /**
     * User constructor.
     * @param int|null $id
     * @param string $email
     * @param string $password
     * @param int    $role
     * @param string $activationToken
     */
    public function __construct(
        ?int $id,
        string $email,
        string $password,
        int    $role,
        string $activationToken
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->activationToken = $activationToken;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getRole(): int
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getActivationToken(): string
    {
        return $this->activationToken;
    }

    /**
     * @return int|null
     */
    public function getJWTIdentifier(): ?int
    {
        return $this->getId();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims() : array
    {
        return [];
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        /**
         * Entity to array
         * 必要なものだけ渡す
         */

        $id = $this->getId() ? $this->getId() : "";
        return [
            'id' => $id,
            'email' => $this->getEmail(),
            'canRegisterProjects' => $this->can('register-projects'),
        ];
    }

    /**
     * Auth::loginを利用する上で必要
     * @return string
     */
    public function getKeyName(): string
    {
        return 'id';
    }
}
