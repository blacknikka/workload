<?php

namespace Tests\Unit\Http\Requests\ReportComment;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ReportComment\GetReportCommentByWeekDay;
use Carbon\Carbon;

class GetReportCommentByWeekDayTest extends TestCase
{
    /** @var GetReportCommentByWeekDay */
    private $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new GetReportCommentByWeekDay();
    }

    /**
     * @test
     * @dataProvider dataProviderRules
     */
    public function validate_ruleの確認(
        $id,
        $week,
        $expect
    )
    {
        $rule = $this->sut->rules();

        $dataList = [
            'id' => $id,
            'week' => $week,
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
                => [1, $date, true],
            'id.型違い正常(文字は数値)'
                => ['1', $date, true],
            'id.型違いエラー'
                => ['aaa', $date, false],
            'id.nullエラー'
                => [null, $date, false],
            'date.型違いエラー'
                => [1, 1, false],
            'date.nullエラー'
                => [1, null, false],
            'date.テンプレ違い'
                => [1, (Carbon::now())->format('Y/m/d'), false],
            'date.テンプレ違い'
                => [1, (Carbon::now())->format('d-m-Y'), false],
        ];
    }
}
