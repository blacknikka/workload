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

use App\Domain\User\Department;

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
    private $name;
    private $department;
    private $email;
    private $password;
    private $role;

    /**
     * User constructor.
     * @param int|null $id
     * @param string $name
     * @param Department $department
     * @param string $email
     * @param string $password
     * @param int    $role
     */
    public function __construct(
        ?int $id,
        string $name,
        Department $department,
        string $email,
        string $password,
        int    $role
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->department = $department;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Department
     */
    public function getDepartment(): Department
    {
        return $this->department;
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
            'name' => $this->name,
            'department' => $this->department->toArray(),
            'email' => $this->getEmail(),
            'canRegisterProjects' => $this->can('register-projects'),
        ];
    }

    /**
     * to string
     *
     * @return string
     */
    public function __toString() : string
    {
        return json_encode($this->toArray());
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
