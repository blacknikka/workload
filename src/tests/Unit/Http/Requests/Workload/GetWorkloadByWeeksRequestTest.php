<?php

namespace Tests\Unit\Http\Requests\Workload;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Workload\GetWorkloadByWeeksRequest;

class GetWorkloadByWeeksRequestTest extends TestCase
{
    /** @var GetWorkloadByWeeksRequest */
    private $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new GetWorkloadByWeeksRequest();
    }

    /**
     * @test
     * @dataProvider dataProviderRules
     */
    public function validate_ruleの確認($id, $week, $expect)
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
        return [
            '正常'
                => [1, '2019-07-01', true],
            'id.型違い正常(文字は数値)'
                => ['1', '2019-07-01', true],
            'id.型違いエラー'
                => ['aaa', '2019-07-01', false],
            'id.nullエラー'
                => [null, '2019-07-01', false],
            'week.型違いエラー'
                => [1, 'aiueo', false],
            'week.nullエラー'
                => [1, null, false],
            'week.formatエラー1'
                => [1, '2019-07-1', false],
            'week.formatエラー2'
                => [1, '2019/07/01', false],
            'week.formatエラー2'
                => [1, '2019-7-1', false],
            'week.formatエラー3'
                => [1, '2019-07-1', false],
            'week.日付としてもおかしい1'
                => [1, '2019-7-50', false],
            'week.日付としてもおかしい2'
                => [1, '2019-00-01', false],
            'week.日付としてもおかしい3'
                => [1, '2019-01-00', false],
        ];
    }
}
