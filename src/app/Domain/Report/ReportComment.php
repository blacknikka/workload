<?php
declare(strict_types=1);

namespace App\Domain\Report;

use App\Domain\User\User;

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

    /**
     * ReportComment constructor.
     * @param int|null $id
     * @param User $user
     * @param string $reportComment
     * @param string $reportOpinion
     */
    public function __construct(
        ?int $id,
        User $user,
        string $reportComment,
        string $reportOpinion
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->reportComment = $reportComment;
        $this->reportOpinion = $reportOpinion;
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
     * @return array
     */
    public function toArray() : array
    {
        return [
            'id' => $this->id,
            'user' => $this->user->toArray(),
            'report_comment' => $this->reportComment,
            'report_opinion' => $this->reportOpinion,
        ];
    }
}
