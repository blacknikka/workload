<?php

namespace Tests\Unit\Http\Requests\GetReportComment;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ReportComment\GetReportCommentByIdRequest;

class GetReportCommentByIdRequestTest extends TestCase
{
    /** @var GetReportCommentByIdRequest */
    private $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new GetReportCommentByIdRequest();
    }

    /**
     * @test
     * @dataProvider dataProviderRules
     */
    public function validate_ruleの確認($id, $expect)
    {
        $rule = $this->sut->rules();

        $dataList = [
            'id' => $id,
        ];
        $validator = Validator::make($dataList, $rule);
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
    }

    public function dataProviderRules()
    {
        return [
            '正常'
                => [1, true],
            'id.型違い正常(文字は数値)'
                => ['1', true],
            'id.型違いエラー'
                => ['aaa', false],
            'id.nullエラー'
                => [null, false],
        ];
    }
}
