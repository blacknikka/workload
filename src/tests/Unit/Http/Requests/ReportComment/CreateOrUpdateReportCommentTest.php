<?php

namespace Tests\Unit\Http\Requests\ReportComment;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ReportComment\CreateOrUpdateReportComment;
use Carbon\Carbon;

class CreateOrUpdateReportCommentTest extends TestCase
{
    /** @var CreateOrUpdateReportComment */
    private $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new CreateOrUpdateReportComment();
    }

    /**
     * @test
     * @dataProvider dataProviderRules
     */
    public function validate_ruleの確認(
        $id,
        $user_id,
        $reportComment,
        $reportOpinion,
        $date,
        $expect
    )
    {
        $rule = $this->sut->rules();

        $dataList = [
            'id' => $id,
            'user_id' => $user_id,
            'report_comment' => $reportComment,
            'report_opinion' => $reportOpinion,
            'date' => $date,
        ];
        $validator = Validator::make($dataList, $rule);
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
    }

    public function dataProviderRules()
    {
        $date = (Carbon::now())->format('Y-m-d');
        return [
            '正常'
                => [1, 1, 'str', 'str', $date, true],
            'id.型違い正常(文字は数値)'
                => ['1', 1, 'str', 'str', $date, true],
            'id.型違いエラー'
                => ['aaa', 1, 'str', 'str', $date, false],
            'id.nullエラー'
                => [null, 1, 'str', 'str', $date, true],
            'userId.型違い正常(文字は数値)'
                => [1, '1', 'str', 'str', $date, true],
            'userId.型違いエラー'
                => [1, 'aaa', 'str', 'str', $date, false],
            'userId.nullエラー'
                => [1, null, 'str', 'str', $date, false],
            'usereport_commentId.型違いエラー'
                => [1, 1, 1, 'str', $date, false],
            'usereport_commentId.nullエラー'
                => [1, 1, null, 'str', $date, false],
            'usereport_commentId.型違いエラー'
                => [1, 1, 'str', 1, $date, false],
            'usereport_commentId.nullエラー'
                => [1, 1, 'str', null, $date, false],
            'date.型違いエラー'
                => [1, 1, 1, 'str', 1, false],
            'date.nullエラー'
                => [1, 1, null, 'str', null, false],
            'date.テンプレ違い'
                => [1, 1, 'str', 'str', (Carbon::now())->format('Y/m/d'), false],
            'date.テンプレ違い'
                => [1, 1, 'str', 'str', (Carbon::now())->format('d-m-Y'), false],
        ];
    }
}
