<?php

namespace Tests\Unit\Domain\Workload\faker;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Unit\Domain\Workload\faker\WorkloadFaker;
use App\Domain\Workload\Workload;
use Carbon\Carbon;

class WorkloadFakerTest extends TestCase
{
    /** @test */
    public function Fakerでデータを作成する()
    {
        $data = WorkloadFaker::create(3);
        $this->assertSame(3, count($data));
        collect($data)->each(function ($v) {
            $this->assertTrue($v instanceof Workload);
        });
    }

    /** @test */
    public function FakerでIDがnullで作成する()
    {
        $data = WorkloadFaker::createWithNullId(3);
        $this->assertSame(3, count($data));
        collect($data)->each(function ($v) {
            $this->assertTrue($v instanceof Workload);
            $this->assertSame($v->getId(), null);
        });
    }

    /** @test */
    public function UserIdを指定して作成する()
    {
        $data = WorkloadFaker::create(3, 5);
        $this->assertSame(3, count($data));

        collect($data)->each(
            function ($v) {
                $this->assertTrue($v instanceof Workload);
                $this->assertEquals(5, $v->getUserId());
            }
        );
    }

    /** @test */
    public function UserIdを指定して作成する_null()
    {
        $data = WorkloadFaker::createWithNullId(3, 5);
        $this->assertSame(3, count($data));

        collect($data)->each(
            function ($v) {
                $this->assertTrue($v instanceof Workload);
                $this->assertEquals(5, $v->getUserId());
                $this->assertNull($v->getId());
            }
        );
    }

    /** @test */
    public function 日付を指定して作成する()
    {
        $date = new Carbon('2019-7-1');

        $data = WorkloadFaker::create(3, null, $date);
        $this->assertSame(3, count($data));

        collect($data)->each(
            function ($v) use ($date) {
                $this->assertTrue($v instanceof Workload);
                $this->assertEquals($date, $v->getDate());
            }
        );
    }

    /** @test */
    public function 日付を指定して作成する_null()
    {
        $date = new Carbon('2019-7-1');

        $data = WorkloadFaker::createWithNullId(3, null, $date);
        $this->assertSame(3, count($data));

        collect($data)->each(
            function ($v) use ($date) {
                $this->assertTrue($v instanceof Workload);
                $this->assertSame($v->getId(), null);
                $this->assertEquals($date, $v->getDate());
            }
        );
    }
}
