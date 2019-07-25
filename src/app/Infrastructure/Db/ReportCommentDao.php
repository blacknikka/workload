<?php
declare(strict_types=1);

namespace App\Infrastructure\Db;

use App\Domain\User\User;
use App\Domain\Report\ReportComment;
use App\Infrastructure\Db\UserDao;
use Carbon\Carbon;
use DB;

/**
 * Class ReportCommentDao
 * @package App\Infrastructure\Db
 */
class ReportCommentDao
{
    const REPORT_TABLE_NAME = 'report';
    const USER_TABLE_NAME = 'user';

    /**
     * UserDao
     *
     * @var UserDao
     */
    private $userDao;

    /**
     * コンストラクタ
     *
     * @param UserDao $userDao
     */
    public function __construct(
        UserDao $userDao
    ) {
        $this->userDao = $userDao;
    }

    /**
     * @param int $reportCommentId
     * @return ReportComment|null
     */
    public function find(int $reportCommentId) : ?ReportComment
    {
        $queryResult = Db::table(self::REPORT_TABLE_NAME)
            ->where(self::REPORT_TABLE_NAME . '.id', $reportCommentId)
            ->select([
                'id',
                'user_id',
                'report_comment',
                'report_opinion',
            ])
            ->first();

        if (is_null($queryResult)) {
            // nullの場合
            return null;
        } else {
            // 非nullの場合
            $user = $this->userDao->find($queryResult->user_id);

            return is_null($user) ? null : $this->newFromQueryResult($queryResult, $user);
        }
    }

    /**
     * save
     *
     * @param ReportComment $reportComment
     * @return integer|null
     */
    public function save(ReportComment $reportComment) : ?int
    {
        $now = Carbon::now();
        $queryResult = DB::table(self::REPORT_TABLE_NAME)
            ->insertGetId([
                'id' => null,
                'user_id' => $reportComment->getUser()->getId(),
                'report_comment' => $reportComment->getReportComment(),
                'report_opinion' => $reportComment->getReportOpinion(),
            ]);

        return $queryResult;
    }

    /**
     * Reportを更新する
     *
     * @param ReportComment $reportComment
     * @return boolean
     */
    public function update(ReportComment $reportComment) : bool
    {
        if (is_null($reportComment->getId())) {
            // nullなら更新できない
            return false;
        }

        $queryResult = DB::table(self::REPORT_TABLE_NAME)
            ->where('id', $reportComment->getId())
            ->update(
                [
                    'id' => $reportComment->getId(),
                    'user_id' => $reportComment->getUser()->getId(),
                    'report_comment' => $reportComment->getReportComment(),
                    'report_opinion' => $reportComment->getReportOpinion(),
                ]
            );

        if ($queryResult > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param \stdClass $queryResult
     * @param User $user
     * @return ReportComment
     */
    private function newFromQueryResult(
        \stdClass $queryResult,
        User $user
    ) : ReportComment
    {
        return new ReportComment(
            $queryResult->id,
            $user,
            $queryResult->report_comment,
            $queryResult->report_opinion
        );
    }
}
