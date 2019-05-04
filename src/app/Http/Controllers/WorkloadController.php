<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Db\WorkloadDao;
use App\Http\Requests\Workload\GetWorkloadRequest;
use Illuminate\Http\Response;

class WorkloadController extends Controller
{
    /** @var WorkloadDao */
    private $workloadDao;

    public function __construct(WorkloadDao $workloadDao)
    {
        $this->workloadDao = $workloadDao;
    }

    /**
     * Idから工数を取得する
     *
     * @param GetWorkloadRequest $request
     * @return JsonResponse
     */
    public function getWorkloadById(GetWorkloadRequest $request, int $id) : JsonResponse
    {
        $result = $this->workloadDao->find($id);

        if (is_null($result)) {
            return response()->json(
                [],
                Response::HTTP_NOT_FOUND,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } else {
            return response()->json(
                $result->toArray(),
                Response::HTTP_OK,
                [],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * UserIdから工数を取得する
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getWorkloadByUserId(GetWorkloadRequest $request) : JsonResponse
    {
        $result = $this->workloadDao->find($id);
    }
}
