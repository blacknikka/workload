<?php

namespace Tests\Unit\Domain\Workload\faker;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Unit\Domain\Workload\faker\WorkloadFaker;
use App\Domain\Workload\Workload;

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
}
