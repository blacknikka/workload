<?php

namespace Tests\Unit\Domain\Workload\faker;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Unit\Domain\Workload\faker\ProjectFaker;
use App\Domain\Workload\Project;

class ProjectFakerTest extends TestCase
{
    /** @test */
    public function Fakerでデータを作成する()
    {
        $data = ProjectFaker::create(3);
        $this->assertSame(3, count($data));
        collect($data)->each(function ($v) {
            $this->assertTrue($v instanceof Project);
        });
    }

    /** @test */
    public function FakerでIDがnullで作成する()
    {
        $data = ProjectFaker::createWithNullId(3);
        $this->assertSame(3, count($data));
        collect($data)->each(function ($v) {
            $this->assertTrue($v instanceof Project);
            $this->assertSame($v->getId(), null);
        });
    }
}
