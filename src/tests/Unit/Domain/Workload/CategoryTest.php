<?php

namespace Tests\Unit\Domain\Workload;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Workload\Category;
use Tests\Unit\Domain\Workload\faker\CategoryFaker;
use Faker\Generator as Faker;

class CategoryTest extends TestCase
{
    /**
     * @test
     */
    public function Categoryインスタンス()
    {
        $faker = app()->make(Faker::class);

        $category = CategoryFaker::create(1);

        $id = $category[0]->getId();
        $name = $category[0]->getName();
        $comment = $category[0]->getComment();

        $categ = new Category(
            $id,
            $name,
            $comment
        );

        $this->assertSame($categ->getId(), $id);
        $this->assertSame($categ->getName(), $name);
        $this->assertSame($categ->getComment(), $comment);
    }
}
