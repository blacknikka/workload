<?php

namespace Tests\Unit\Domain\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Generator as Faker;
use App\Domain\User\User;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function Userインスタンス()
    {
        $faker = app()->make(Faker::class);

        $id = $faker->unique()->randomNumber() + 1;
        $email = $faker->email();
        $password = bcrypt($faker->word());
        $role = 10;
        $activationToken = '';

        $user = new User(
            $id,
            $email,
            $password,
            $role,
            $activationToken
        );

        $this->assertSame($user->getId(), $id);
        $this->assertSame($user->getEmail(), $email);
        $this->assertSame($user->getPassword(), $password);
        $this->assertSame($user->getRole(), $role);
        $this->assertSame($user->getActivationToken(), $activationToken);
        $this->assertSame($user->getJWTIdentifier(), $id);
        $this->assertSame($user->getJWTCustomClaims(), []);
    }
}
