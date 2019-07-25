<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Db\ReportCommentDao;
use App\Infrastructure\Db\UserDao;
use App\Http\Requests\ReportComment\GetReportCommentByIdRequest;
use App\Http\Requests\ReportComment\CreateOrUpdateReportComment;
use Illuminate\Http\Response;
use App\Domain\Report\ReportComment;

class ReportCommentController extends Controller
{
    /** @var ReportCommentDao */
    private $reportCommentDao;

    /** @var UserDao */
    private $userDao;

    public function __construct(ReportCommentDao $reportCommentDao, UserDao $userDao)
    {
        $this->reportCommentDao = $reportCommentDao;
        $this->userDao = $userDao;
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

    /**
     * saveを行う（登録か更新)
     *
     * @param CreateOrUpdateReportComment $request
     * @return JsonResponse
     */
    public function createOrUpdateReportComment(
        CreateOrUpdateReportComment $request) : JsonResponse
    {
        $id = $request->get('id');
        $userId = $request->get('user_id');
        $reportComment = $request->get('report_comment');
        $reportOpinion = $request->get('report_opinion');

        $user = $this->userDao->find($userId);

        // userがないとエラー
        if (is_null($user)) {
            return response()->json(
                [
                    'result' => false,
                    'message' => 'user is not found',
                ],
                Response::HTTP_BAD_REQUEST,
                [],
                JSON_UNESCAPED_UNICODE
            );
        }

        $saveResult = $this->reportCommentDao->save(
            new ReportComment(
                $id,
                $user,
                $reportComment,
                $reportOpinion
            )
        );

        if (is_null($saveResult)) {
            return response()->json(
                [
                    'result' => false,
                    'message' => 'save error',
                ],
                Response::HTTP_BAD_REQUEST,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } else {
            return response()->json(
                [
                    'result' => true,
                    'message' => 'The Report Comment has been saved',
                ],
                Response::HTTP_OK,
                [],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
