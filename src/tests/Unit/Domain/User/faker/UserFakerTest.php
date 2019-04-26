<?php

namespace Tests\Unit\Domain\User\faker;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Unit\Domain\User\faker\UserFaker;
use App\Domain\User\User;

class UserFakerTest extends TestCase
{
    /** @test */
    public function Fakerでデータを作成する()
    {
        $data = UserFaker::create(3);
        $this->assertSame(3, count($data));
        collect($data)->each(function ($v) {
            $this->assertTrue($v instanceof User);
        });
    }

    /** @test */
    public function FakerでIDがnullで作成する()
    {
        $data = UserFaker::createWithNullId(3);
        $this->assertSame(3, count($data));
        collect($data)->each(function ($v) {
            $this->assertTrue($v instanceof User);
            $this->assertSame($v->getId(), null);
        });
    }
}
