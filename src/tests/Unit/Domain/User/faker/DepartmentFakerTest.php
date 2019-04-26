<?php

namespace Tests\Unit\Domain\User\faker;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Unit\Domain\User\faker\DepartmentFaker;
use App\Domain\User\Department;

class DepartmentFakerTest extends TestCase
{
    /** @test */
    public function Fakerでデータを作成する()
    {
        $data = DepartmentFaker::create(3);
        $this->assertSame(3, count($data));
        collect($data)->each(function ($v) {
            $this->assertTrue($v instanceof Department);
        });
    }

    /** @test */
    public function FakerでIDがnullで作成する()
    {
        $data = DepartmentFaker::createWithNullId(3);
        $this->assertSame(3, count($data));
        collect($data)->each(function ($v) {
            $this->assertTrue($v instanceof Department);
            $this->assertSame($v->getId(), null);
        });
    }
}
