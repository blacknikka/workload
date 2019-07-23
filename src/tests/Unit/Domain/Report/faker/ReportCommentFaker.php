<?php

namespace Tests\Unit\Domain\Report\faker;

use Faker\Generator as Faker;
use App\Domain\Report\ReportComment;
use Carbon\Carbon;
use Tests\Unit\Domain\User\faker\UserFaker;

class ReportCommentFaker
{
    /**
     * ReportCommentのデータを作る
     *
     * @param number $number
     * @return ReportComment[]
     */
    public static function create($number) : array
    {
        $faker = app()->make(Faker::class);

        $data = collect()::times($number, function ($number) use ($faker) {
            return new ReportComment(
                $faker->unique()->numberBetween(1, 1000000),
                UserFaker::create(1)[0],
                $faker->sentence(),
                $faker->sentence()
            );
        });
        return $data->all();
    }

    /**
     * DepartmentFakerのデータをIDをnullで作る
     *
     * @param [type] $number
     * @return ReportComment[]
     */
    public static function createWithNullId($number) : array
    {
        /** @var Faker */
        $faker = app()->make(Faker::class);

        $data = collect()::times($number, function ($number) use ($faker) {
            return new ReportComment(
                null,
                UserFaker::create(1)[0],
                $faker->sentence(),
                $faker->sentence()
            );
        });
        return $data->all();
    }
}
