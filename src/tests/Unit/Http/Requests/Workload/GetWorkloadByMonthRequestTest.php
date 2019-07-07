<?php

namespace Tests\Unit\Http\Requests\Workload;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Workload\GetWorkloadByMonthRequest;

class GetWorkloadByMonthRequestTest extends TestCase
{
    /** @var GetWorkloadByMonthRequest */
    private $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new GetWorkloadByMonthRequest();
    }

    /**
     * @test
     * @dataProvider dataProviderRules
     */
    public function validate_ruleの確認($id, $month, $expect)
    {
        $rule = $this->sut->rules();

        $dataList = [
            'id' => $id,
            'month' => $month,
        ];
        $validator = Validator::make($dataList, $rule);
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
    }

    public function dataProviderRules()
    {
        return [
            '正常'
                => [1, '2019-07', true],
            'id.型違い正常(文字は数値)'
                => ['1', '2019-07', true],
            'id.型違いエラー'
                => ['aaa', '2019-07', false],
            'id.nullエラー'
                => [null, '2019-07', false],
            'month.型違いエラー'
                => [1, 'aiueo', false],
            'month.nullエラー'
                => [1, null, false],
            'month.formatエラー1'
                => [1, '2019-7', false],
            'month.formatエラー2'
                => [1, '2019/7', false],
            'month.formatエラー2'
                => [1, '2019-07-01', false],
            'month.日付としてもおかしい1'
                => [1, '2019-70', false],
            'month.日付としてもおかしい2'
                => [1, '2019-00', false],
            'month.日付としてもおかしい3'
                => [1, '2019-13', false],
        ];
    }
}
