<?php

namespace Tests\Unit\Domain\User\faker;

use Faker\Generator as Faker;
use App\Domain\User\User;
use Carbon\Carbon;
use Tests\Unit\Domain\User\faker\DepartmentFaker;

class UserFaker
{
    /**
     * Userのデータを作る
     *
     * @param number $number
     * @return User[]
     */
    public static function create($number) : array
    {
        $faker = app()->make(Faker::class);

        $data = collect()::times($number, function ($number) use ($faker) {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            return new User(
                $faker->unique()->randomNumber() + 1,
                $faker->name(),
                DepartmentFaker::create(1)[0],
                $faker->email(),
                bcrypt($faker->word()),
                1,
                '',
                $faker->password()
            );
        });
        return $data->all();
    }

    /**
     * UserのデータをIDをnullで作る
     *
     * @param [type] $number
     * @return User[]
     */
    public static function createWithNullId($number) : array
    {
        /** @var Faker */
        $faker = app()->make(Faker::class);

        $data = collect()::times($number, function ($number) use ($faker) {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $faker->unique();
            return new User(
                null,
                $faker->name(),
                DepartmentFaker::create(1)[0],
                $faker->email(),
                bcrypt($faker->word()),
                1,
                '',
                $faker->password()
            );
        });
        return $data->all();
    }
}
