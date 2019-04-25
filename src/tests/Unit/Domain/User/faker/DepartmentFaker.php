<?php

namespace Tests\Unit\Domain\User\faker;

use Faker\Generator as Faker;
use App\Domain\User\Department;
use Carbon\Carbon;

class DepartmentFaker
{
    /**
     * Departmentのデータを作る
     *
     * @param number $number
     * @return Department[]
     */
    public static function create($number) : array
    {
        $faker = app()->make(Faker::class);

        $data = collect()::times($number, function ($number) use ($faker) {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            return new Department(
                $faker->unique()->randomNumber() + 1,
                $faker->word(),
                $faker->word(),
                $faker->sentence()
            );
        });
        return $data->all();
    }

    /**
     * DepartmentFakerのデータをIDをnullで作る
     *
     * @param [type] $number
     * @return Department[]
     */
    public static function createWithNullId($number) : array
    {
        /** @var Faker */
        $faker = app()->make(Faker::class);

        $data = collect()::times($number, function ($number) use ($faker) {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $faker->unique();
            return new Department(
                null,
                $faker->word(),
                $faker->word(),
                $faker->sentence()
            );
        });
        return $data->all();
    }
}
