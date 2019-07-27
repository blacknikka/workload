<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Db\ReportCommentDao;
use App\Infrastructure\Db\UserDao;
use App\Http\Requests\ReportComment\GetReportCommentByIdRequest;
use App\Http\Requests\ReportComment\CreateOrUpdateReportComment;
use App\Http\Requests\ReportComment\GetReportCommentByWeekDay;
use Illuminate\Http\Response;
use App\Domain\Report\ReportComment;
use Carbon\Carbon;

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

    /**
     * ReportCommentのIDからReportCommentのデータを検索する
     *
     * @param GetReportCommentByIdRequest $request
     * @param integer $id
     * @return JsonResponse
     */
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
     * UserIdと日付からコメントを取得する
     * 受け取った$weekから週の初めの日を計算して取得する
     *
     * @param GetReportCommentByWeekDay $request
     * @param integer $userId
     * @param string $week
     * @return JsonResponse
     */
    public function getReportCommentByUserId(
        GetReportCommentByWeekDay $request,
        int $userId,
        string $week
    ) : JsonResponse
    {
        // 日付情報からCarbonを作成
        // 週の最初を日曜日に設定する
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

        // 日付を取得
        // $weekは日付
        $date = (new Carbon($week))->startOfWeek();

        $findResult = $this->reportCommentDao->findByWeekDay($userId, $date);

        if (is_null($findResult)) {
            return response()->json(
                [
                    'result' => false,
                    'message' => 'Record not found',
                ],
                Response::HTTP_BAD_REQUEST,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } else {
            return response()->json(
                [
                    'result' => true,
                    'data' => [
                        'id' => $findResult->getId(),
                        'report_comment' => $findResult->getReportComment(),
                        'report_opinion' => $findResult->getReportOpinion(),
                        'date' => $findResult->getdate()->format('Y-m-d'),
                    ],
                ],
                Response::HTTP_OK,
                [],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * saveを行う（登録か更新)
     * 指定された最初の日付として設定する（週に１回のコメントしかないので）
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

        // 日付情報からCarbonを作成
        // 週の最初を日曜日に設定する
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

        // 日付を取得
        // $weekは日付
        $reportDate = $request->get('date');
        $reportDate = (new Carbon($reportDate))->startOfWeek();

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
                $reportOpinion,
                $reportDate
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
