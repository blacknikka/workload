<?php

namespace Tests\Unit\Domain\Workload\faker;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Unit\Domain\Workload\faker\CategoryFaker;
use App\Domain\Workload\Category;

class CategoryFakerTest extends TestCase
{
    /** @test */
    public function Fakerでデータを作成する()
    {
        $data = CategoryFaker::create(3);
        $this->assertSame(3, count($data));
        collect($data)->each(function ($v) {
            $this->assertTrue($v instanceof Category);
        });
    }

    /** @test */
    public function FakerでIDがnullで作成する()
    {
        $data = CategoryFaker::createWithNullId(3);
        $this->assertSame(3, count($data));
        collect($data)->each(function ($v) {
            $this->assertTrue($v instanceof Category);
            $this->assertSame($v->getId(), null);
        });
    }
}
