<?php

namespace Tests\Unit\Domain\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Generator as Faker;
use App\Domain\User\Department;
use Tests\Unit\Domain\User\faker\DepartmentFaker;

class DepartmentTest extends TestCase
{
    /**
     * @test
     */
    public function Departmentインスタンス()
    {
        $users = DepartmentFaker::create(1);

        $id = $users[0]->getId();
        $name = $users[0]->getName();
        $sectionName = $users[0]->getSectionName();
        $comment = $users[0]->getComment();

        $user = new Department(
            $id,
            $name,
            $sectionName,
            $comment
        );

        $this->assertSame($user->getId(), $id);
        $this->assertSame($user->getName(), $name);
        $this->assertSame($user->getSectionName(), $sectionName);
        $this->assertSame($user->getComment(), $comment);
    }
}
