<?php

namespace Tests\Unit\Http\Requests\ReportComment;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ReportComment\CreateOrUpdateReportComment;

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
        $expect
    )
    {
        $rule = $this->sut->rules();

        $dataList = [
            'id' => $id,
            'user_id' => $user_id,
            'report_comment' => $reportComment,
            'report_opinion' => $reportOpinion,
        ];
        $validator = Validator::make($dataList, $rule);
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
    }

    public function dataProviderRules()
    {
        return [
            '正常'
                => [1, 1, 'str', 'str', true],
            'id.型違い正常(文字は数値)'
                => ['1', 1, 'str', 'str', true],
            'id.型違いエラー'
                => ['aaa', 1, 'str', 'str', false],
            'id.nullエラー'
                => [null, 1, 'str', 'str', true],
            'userId.型違い正常(文字は数値)'
                => [1, '1', 'str', 'str', true],
            'userId.型違いエラー'
                => [1, 'aaa', 'str', 'str', false],
            'userId.nullエラー'
                => [1, null, 'str', 'str', false],
            'usereport_commentId.型違いエラー'
                => [1, 1, 1, 'str', false],
            'usereport_commentId.nullエラー'
                => [1, 1, null, 'str', false],
            'usereport_commentId.型違いエラー'
                => [1, 1, 'str', 1, false],
            'usereport_commentId.nullエラー'
                => [1, 1, 'str', null, false],
        ];
    }
}
