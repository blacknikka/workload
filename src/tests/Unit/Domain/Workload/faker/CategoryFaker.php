<?php

namespace Tests\Unit\Domain\Workload\faker;

use Faker\Generator as Faker;
use App\Domain\Workload\Category;
use Carbon\Carbon;

class CategoryFaker
{
    /**
     * Categoryのデータを作る
     *
     * @param number $number
     * @return Category[]
     */
    public static function create($number) : array
    {
        $faker = app()->make(Faker::class);

        $data = collect()::times($number, function ($number) use ($faker) {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            return new Category(
                $faker->unique()->randomNumber() + 1,
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
     * @return Category[]
     */
    public static function createWithNullId($number) : array
    {
        /** @var Faker */
        $faker = app()->make(Faker::class);

        $data = collect()::times($number, function ($number) use ($faker) {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $faker->unique();
            return new Category(
                null,
                $faker->word(),
                $faker->sentence()
            );
        });
        return $data->all();
    }
}
