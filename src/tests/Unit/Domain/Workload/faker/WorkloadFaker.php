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
     * @param integer $number
     * @param integer|null $userId
     * @param Carbon|null $targetDate
     * @return array
     */
    public static function create(int $number, ?int $userId = null, ?Carbon $targetDate = null) : array
    {
        $faker = app()->make(Faker::class);

        // 日付をチェックする
        $date = $targetDate === null ? Carbon::today(): new Carbon($targetDate);

        $data = collect()::times(
            $number, function ($number) use ($faker, $userId, $date) {
                $now = Carbon::now()->format('Y-m-d H:i:s');
                return new Workload(
                    $faker->unique()->randomNumber() + 1,
                    $userId === null ? $faker->unique()->randomNumber() + 1 : $userId,
                    $faker->unique()->randomNumber(),
                    $faker->unique()->randomNumber(),
                    $faker->randomFloat(2, 0, 24),
                    $date
                );
            }
        );
        return $data->all();
    }

    /**
     * WorkloadFakerのデータをIDをnullで作る
     *
     * @param integer $number
     * @param integer|null $userId
     * @param Carbon|null $targetDate
     * @return array
     */
    public static function createWithNullId(int $number, ?int $userId = null, ?Carbon $targetDate = null) : array
    {
        /** @var Faker */
        $faker = app()->make(Faker::class);

        // 日付をチェックする
        $date = $targetDate === null ? Carbon::today(): new Carbon($targetDate);

        $data = collect()::times(
            $number, function ($number) use ($faker, $userId, $date) {
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $faker->unique();
                return new Workload(
                    null,
                    $userId === null ? $faker->unique()->randomNumber() + 1 : $userId,
                    $faker->unique()->randomNumber(),
                    $faker->unique()->randomNumber(),
                    $faker->randomFloat(2, 0, 24),
                    $date
                );
            }
        );
        return $data->all();
    }
}
