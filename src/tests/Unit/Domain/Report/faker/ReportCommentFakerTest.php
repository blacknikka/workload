<?php

namespace Tests\Unit\Domain\Report\faker;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Unit\Domain\Report\faker\ReportCommentFaker;
use App\Domain\Report\ReportComment;

class ReportCommentFakerTest extends TestCase
{
    /** @test */
    public function Fakerでデータを作成する()
    {
        $data = ReportCommentFaker::create(3);
        $this->assertSame(3, count($data));
        collect($data)->each(function ($v) {
            $this->assertTrue($v instanceof ReportComment);
        });
    }

    /** @test */
    public function FakerでIDがnullで作成する()
    {
        $data = ReportCommentFaker::createWithNullId(3);
        $this->assertSame(3, count($data));
        collect($data)->each(function ($v) {
            $this->assertTrue($v instanceof ReportComment);
            $this->assertSame($v->getId(), null);
        });
    }
}
