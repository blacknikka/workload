<?php

namespace Tests\Unit\Domain\Workload;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Workload\Project;
use Tests\Unit\Domain\Workload\faker\ProjectFaker;
use Faker\Generator as Faker;

class ProjectTest extends TestCase
{
    /**
     * @test
     */
    public function Projectインスタンス()
    {
        $faker = app()->make(Faker::class);

        $project = ProjectFaker::create(1);

        $id = $project[0]->getId();
        $name = $project[0]->getName();
        $comment = $project[0]->getComment();

        $proj = new Project(
            $id,
            $name,
            $comment
        );

        $this->assertSame($proj->getId(), $id);
        $this->assertSame($proj->getName(), $name);
        $this->assertSame($proj->getComment(), $comment);
    }

    /** @test */
    public function ProjectToArray()
    {
        $faker = app()->make(Faker::class);

        $project = ProjectFaker::create(1);

        $id = $project[0]->getId();
        $name = $project[0]->getName();
        $comment = $project[0]->getComment();

        $proj = new Project(
            $id,
            $name,
            $comment
        );

        $this->assertSame(
            $proj->toArray(),
            [
                'id' => $id,
                'name' => $name,
                'comment' => $comment,
            ]
        );
    }
}
