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
                'date',
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
        if ($this->isReportCommentExist($reportComment)) {
            // update
            return $this->update($reportComment);
        } else {
            // insert
            return $this->insert($reportComment);
        }
    }

    /**
     * insert
     *
     * @param ReportComment $reportComment
     * @return integer|null
     */
    private function insert(ReportComment $reportComment) : ?int
    {
        $queryResult = DB::table(self::REPORT_TABLE_NAME)
        ->insertGetId([
            'id' => null,
            'user_id' => $reportComment->getUser()->getId(),
            'report_comment' => $reportComment->getReportComment(),
            'report_opinion' => $reportComment->getReportOpinion(),
            'date' => $reportComment->getDate(),
        ]);

        return $queryResult;
    }

    /**
     * Reportを更新する
     *
     * @param ReportComment $reportComment
     * @return integer|null
     */
    private function update(ReportComment $reportComment) : ?int
    {
        $date = $reportComment->getDate()->format('Y-m-d');
        $queryResult = DB::table(self::REPORT_TABLE_NAME)
            ->whereDate('date', $date)
            ->update(
                [
                    'user_id' => $reportComment->getUser()->getId(),
                    'report_comment' => $reportComment->getReportComment(),
                    'report_opinion' => $reportComment->getReportOpinion(),
                    'date' => $reportComment->getDate(),
                ]
            );

        var_dump($queryResult);
        if ($queryResult > 0) {
            return $queryResult;
        } else {
            return null;
        }
    }

    /**
     * 日付情報があるかどうか
     *
     * @param ReportComment $reportComment
     * @return boolean
     */
    private function isReportCommentExist(ReportComment $reportComment) : bool
    {
        return DB::table(self::REPORT_TABLE_NAME)
            ->where('user_id', $reportComment->getUser()->getId())
            ->whereDate('date', $reportComment->getDate())
            ->exists();
    }

    /**
     * 日付からReport情報を取得する
     *
     * @param integer $userId
     * @param Carbon $weekDay
     * @return ReportComment|null
     */
    public function findByWeekDay(int $userId, Carbon $weekDay) : ?ReportComment
    {
        // 指定された日付のReportCommentを取得する
        $queryResult = DB::table(self::REPORT_TABLE_NAME)
            ->where('user_id', $userId)
            ->whereDate('date', '=', $weekDay)
            ->select(
                [
                    'id',
                    'user_id',
                    'report_comment',
                    'report_opinion',
                    'date',
                ]
            )
            ->first();

        if (is_null($queryResult)) {
            // ない場合にはnull
            return null;
        } else {
            $user = $this->userDao->find($queryResult->user_id);
            if (is_null($user)) {
                // null
                return null;
            } else {
                // not null
                return $this->newFromQueryResult($queryResult, $user);
            }
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
            $queryResult->report_opinion,
            new Carbon($queryResult->date)
        );
    }
}
