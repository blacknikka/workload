<?php

namespace Tests\Unit\Domain\Workload\faker;

use Faker\Generator as Faker;
use App\Domain\Workload\Project;
use Carbon\Carbon;

class ProjectFaker
{
    /**
     * Projectのデータを作る
     *
     * @param number $number
     * @return Project[]
     */
    public static function create($number) : array
    {
        $faker = app()->make(Faker::class);

        $data = collect()::times($number, function ($number) use ($faker) {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            return new Project(
                $faker->unique()->randomNumber() + 1,
                $faker->word(),
                $faker->sentence()
            );
        });
        return $data->all();
    }

    /**
     * ProjectFakerのデータをIDをnullで作る
     *
     * @param [type] $number
     * @return Project[]
     */
    public static function createWithNullId($number) : array
    {
        /** @var Faker */
        $faker = app()->make(Faker::class);

        $data = collect()::times($number, function ($number) use ($faker) {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $faker->unique();
            return new Project(
                null,
                $faker->word(),
                $faker->sentence()
            );
        });
        return $data->all();
    }
}
