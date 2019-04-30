<?php

namespace Tests\Unit\Domain\Workload\faker;

use Faker\Generator as Faker;
use App\Domain\Workload\Workload;
use Carbon\Carbon;

class WorkloadFaker
{
    /**
     * Workloadのデータを作る
     *
     * @param number $number
     * @return Workload[]
     */
    public static function create($number) : array
    {
        $faker = app()->make(Faker::class);

        $data = collect()::times($number, function ($number) use ($faker) {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            return new Workload(
                $faker->unique()->randomNumber() + 1,
                $faker->unique()->randomNumber(),
                $faker->unique()->randomNumber(),
                $faker->randomFloat(2, 0, 24),
                Carbon::today()
            );
        });
        return $data->all();
    }

    /**
     * WorkloadFakerのデータをIDをnullで作る
     *
     * @param [type] $number
     * @return Workload[]
     */
    public static function createWithNullId($number) : array
    {
        /** @var Faker */
        $faker = app()->make(Faker::class);

        $data = collect()::times($number, function ($number) use ($faker) {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $faker->unique();
            return new Workload(
                null,
                $faker->unique()->randomNumber(),
                $faker->unique()->randomNumber(),
                $faker->randomFloat(2, 0, 24),
                Carbon::today()
            );
        });
        return $data->all();
    }
}
