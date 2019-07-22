<?php

namespace Tests\Unit\Domain\Report;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Unit\Domain\Report\faker\ReportCommentFaker;
use App\Domain\Report\ReportComment;

class ReportCommentTest extends TestCase
{
    /** @test */
    public function ReportCommentのインスタンス()
    {
        $report = ReportCommentFaker::create(1)[0];

        $id = $report->getId();
        $user = $report->getUser();
        $reportComment = $report->getReportComment();
        $reportOpinion = $report->getReportOpinion();

        $createdReport = new ReportComment(
            $id,
            $user,
            $reportComment,
            $reportOpinion
        );

        $this->assertSame($createdReport->getId(), $id);
        $this->assertSame($createdReport->getUser(), $user);
        $this->assertSame($createdReport->getReportComment(), $reportComment);
        $this->assertSame($createdReport->getReportOpinion(), $reportOpinion);
    }
}
