<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Db\WorkloadDao;
use App\Domain\Workload\Project;
use App\Domain\Workload\Category;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    /** @var WorkloadDao */
    private $workloadDao;

    public function __construct(WorkloadDao $workloadDao)
    {
        $this->workloadDao = $workloadDao;
    }

    public function getProjectAndCategoryList(Request $request) : JsonResponse
    {
        $projectList = $this->workloadDao->getProjectList();
        $categoryList = $this->workloadDao->getCategoryList();

        $projectArray = $projectList->map(
            function (Project $project) {
                return $project->toArray();
            }
        );

        $categoryArray = $categoryList->map(
            function (Category $category) {
                return $category->toArray();
            }
        );

        return response()->json(
            [
                'project' => $projectArray,
                'category' => $categoryArray,
            ],
            Response::HTTP_OK,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }
}
