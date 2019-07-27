<?php
declare(strict_types=1);

namespace App\Domain\Report;

use App\Domain\User\User;
use Carbon\Carbon;

/**
 * Class Report
 * @package App\Domain\Report
 */
class ReportComment
{
    public $id;
    private $user;
    private $reportComment;
    private $reportOpinion;
    private $date;

    /**
     * ReportComment constructor.
     * @param int|null $id
     * @param User $user
     * @param string $reportComment
     * @param string $reportOpinion
     * @param Carbon $date
     */
    public function __construct(
        ?int $id,
        User $user,
        string $reportComment,
        string $reportOpinion,
        Carbon $date
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->reportComment = $reportComment;
        $this->reportOpinion = $reportOpinion;
        $this->date = $date;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getReportComment(): string
    {
        return $this->reportComment;
    }

    /**
     * @return string
     */
    public function getReportOpinion(): string
    {
        return $this->reportOpinion;
    }

    /**
     * 日付の取得
     *
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'id' => $this->id,
            'user' => $this->user->toArray(),
            'report_comment' => $this->reportComment,
            'report_opinion' => $this->reportOpinion,
            'date' => $this->date->format('Y-m-d'),
        ];
    }
}
