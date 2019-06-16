<?php

namespace Tests\Unit\Domain\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Generator as Faker;
use App\Domain\User\User;
use Tests\Unit\Domain\User\faker\UserFaker;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function Userインスタンス()
    {
        $users = UserFaker::create(1);

        $id = $users[0]->getId();
        $name = $users[0]->getName();
        $department = $users[0]->getDepartment();
        $email = $users[0]->getEmail();
        $password = $users[0]->getPassword();
        $role = $users[0]->getRole();

        $user = new User(
            $id,
            $name,
            $department,
            $email,
            $password,
            $role
        );

        $this->assertSame($user->getId(), $id);
        $this->assertSame($user->getName(), $name);
        $this->assertSame($user->getDepartment(), $department);
        $this->assertSame($user->getEmail(), $email);
        $this->assertSame($user->getPassword(), $password);
        $this->assertSame($user->getRole(), $role);
        $this->assertSame($user->getJWTIdentifier(), $id);
        $this->assertSame($user->getJWTCustomClaims(), []);
    }
}
