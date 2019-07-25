<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Db\ReportCommentDao;
use App\Http\Requests\ReportComment\GetReportCommentByIdRequest;
use Illuminate\Http\Response;

class ReportCommentController extends Controller
{
    /** @var ReportCommentDao */
    private $reportCommentDao;

    public function __construct(ReportCommentDao $reportCommentDao)
    {
        $this->reportCommentDao = $reportCommentDao;
    }

    public function getReportCommentById(
        GetReportCommentByIdRequest $request, int $id) : JsonResponse
    {
        $findResult = $this->reportCommentDao->find($id);

        if (is_null($findResult)) {
            return response()->json(
                [
                    'result' => false,
                    'message' => 'id is not found',
                ],
                Response::HTTP_BAD_REQUEST,
                [],
                JSON_UNESCAPED_UNICODE
            );
        }

        return response()->json(
            [
                'result' => true,
                'reportComment' => $findResult->toArray(),
            ],
            Response::HTTP_OK,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }
}
