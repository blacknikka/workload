<?php

namespace Tests\Unit\Http\Requests\Workload;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Workload\GetWorkloadRequest;

class GetWorkloadRequestTest extends TestCase
{
    /** @var GetWorkloadRequest */
    private $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new GetWorkloadRequest();
    }

    /**
     * @test
     * @dataProvider dataProviderRules
     */
    public function validate_ruleの確認($id, $expect)
    {
        $rule = $this->sut->rules();

        $dataList = ['id' => $id];
        $validator = Validator::make($dataList, $rule);
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
    }

    public function dataProviderRules()
    {
        return [
            '正常'
                => [1, true],
            '型違いエラー'
                => ['aaa', false],
            'nullエラー'
                => [null, false],
        ];
    }
}
