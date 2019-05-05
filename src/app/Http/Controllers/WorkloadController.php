<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Db\WorkloadDao;
use App\Http\Requests\Workload\GetWorkloadRequest;
use App\Http\Requests\Workload\SetWorkloadRequest;
use Illuminate\Http\Response;
use App\Domain\Workload\Workload;
use Carbon\Carbon;

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
    public function getWorkloadByUserId(GetWorkloadRequest $request, int $userId) : JsonResponse
    {
        $result = $this->workloadDao->findByUserId($userId);

        if (is_null($result)) {
            return response()->json(
                [],
                Response::HTTP_NOT_FOUND,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } else {
            $array = collect($result)->map(function ($f) {
                return $f->toArray();
            });

            return response()->json(
                $array,
                Response::HTTP_OK,
                [],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * 工数をセットする
     *
     * @param SetWorkloadRequest $request
     * @return JsonResponse
     */
    public function setWorkloadByUserId(SetWorkloadRequest $request) : JsonResponse
    {
        $input = $request->only([
            'user_id',
            'project_id',
            'category_id',
            'amount',
            'date',
        ]);

        $result = $this->workloadDao->save(new Workload(
            null,
            $input['user_id'],
            $input['project_id'],
            $input['category_id'],
            $input['amount'],
            new Carbon($input['date'])
        ));

        if ($result < 0) {
            // 異常
            return response()->json(
                [
                    'result' => 'error',
                    'message' => 'save error',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } else {
            return response()->json(
                [
                    'result' => 'done',
                    'message' => 'no error',
                ],
                Response::HTTP_OK,
                [],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
