<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\WorkloadController;
use App\Infrastructure\Db\WorkloadDao;
use Illuminate\Http\Response;
use Mockery;
use App\Domain\Workload\Workload;
use Illuminate\Support\Collection;
use Tests\Unit\Domain\Workload\faker\ProjectFaker;
use Tests\Unit\Domain\Workload\faker\WorkloadFaker;
use Tests\Unit\Domain\Workload\faker\CategoryFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Carbon\Carbon;
use App\Domain\Workload\Project;
use App\Domain\Workload\Category;

class ProjectControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @var Mockery\MockInterface */
    private $workloadDaoMock;

    public function setUp()
    {
        parent::setUp();

        // 依存をモックする
        $this->workloadDaoMock = Mockery::mock(WorkloadDao::class);

        // 注入
        app()->instance(WorkloadDao::class, $this->workloadDaoMock);
    }

    public function tearDown()
    {
        parent::tearDown();

        // モックをクローズ
        Mockery::close();
    }

    /** @test */
    public function getProjectAndCategoryList_WorkloadDaoのgetProjectListが呼ばれている()
    {
        $projects = ProjectFaker::create(3);
        $this->workloadDaoMock
            ->shouldReceive('getProjectList')
            ->andReturn(collect($projects));

        $categories = CategoryFaker::create(3);
        $this->workloadDaoMock
            ->shouldReceive('getCategoryList')
            ->andReturn(collect($categories));

        $response = $this->getJson(
            route('getProjectAndCategory')
        );
        $response->assertStatus(Response::HTTP_OK);

        $this->workloadDaoMock->shouldHaveReceived('getProjectList');
        $this->workloadDaoMock->shouldHaveReceived('getCategoryList');
    }

    /** @test */
    public function getProjectAndCategoryList_返却が正しい()
    {
        $projects = ProjectFaker::create(3);
        $this->workloadDaoMock
            ->shouldReceive('getProjectList')
            ->andReturn(collect($projects));

        $projectsArray = collect($projects)->map(
            function (Project $project) {
                return $project->toArray();
            }
        )->all();

        $categories = CategoryFaker::create(3);
        $this->workloadDaoMock
            ->shouldReceive('getCategoryList')
            ->andReturn(collect($categories));

        $categoriesArray = collect($categories)->map(
            function (Category $category) {
                return $category->toArray();
            }
        )->all();

        $response = $this->getJson(
            route('getProjectAndCategory')
        )->assertJson(
            [
                'project' => $projectsArray,
                'category' => $categoriesArray,
            ]
        );
    }
}
