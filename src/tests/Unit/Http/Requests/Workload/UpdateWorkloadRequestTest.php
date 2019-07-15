<?php

namespace Tests\Unit\Http\Requests\Workload;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Workload\UpdateWorkloadRequest;

class UpdateWorkloadRequestTest extends TestCase
{
    /** @var UpdateWorkloadRequest */
    private $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new UpdateWorkloadRequest();
    }

    /**
     * @test
     * @dataProvider dataProviderRules
     */
    public function validate_ruleの確認($data, $expect)
    {
        $rule = $this->sut->rules();

        $validator = Validator::make($data, $rule);
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
    }

    public function dataProviderRules()
    {
        return [
            '正常'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => 1,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                true,
            ],
            'workloads not array'
            => [
                [
                    'user_id' => null,
                    'workloads' => 1,
                ],
                false
            ],
            'user_id null'
            => [
                [
                    'user_id' => null,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => 1,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                false,
            ],
            'user_id string'
            => [
                [
                    'user_id' => 'string',
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => 1,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                false,
            ],
            'user_id none'
            => [
                [
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => 1,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                false,
            ],
            'id null'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => null,
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => 1,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                true,
            ],
            'id string'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 'string',
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => 1,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                false,
            ],
            'id none'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => 1,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                false,
            ],
            'project_id null'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => null,
                            'category_id' => 1,
                            'amount' => 1,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                false,
            ],
            'project_id string'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 'string',
                            'category_id' => 1,
                            'amount' => 1,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                false,
            ],
            'project_id none'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'category_id' => 1,
                            'amount' => 1,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                false,
            ],
            'category_id null'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => null,
                            'amount' => 1,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                false,
            ],
            'category_id string'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => 'string',
                            'amount' => 1,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                false,
            ],
            'category_id none'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'amount' => 1,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                false,
            ],
            'amount null'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => null,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                false,
            ],
            'amount string'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => 'string',
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                false,
            ],
            'amount none'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => 1,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                false,
            ],
            'amount float'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => 0.5,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                true,
            ],
            'amount negative'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => -0.5,
                            'date' => '2019-5-1'
                        ]
                    ],
                ],
                false,
            ],
            'date null'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => 1,
                            'date' => null,
                        ]
                    ],
                ],
                false,
            ],
            'date int'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => 1,
                            'date' => 1,
                        ]
                    ],
                ],
                false,
            ],
            'date not date'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => 1,
                            'date' => '20202020202',
                        ]
                    ],
                ],
                false,
            ],
            'date correct'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => 1,
                            'date' => '20180501',
                        ]
                    ],
                ],
                true,
            ],
            'date correct2'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => 1,
                            'date' => '2018-5-1',
                        ]
                    ],
                ],
                true,
            ],
            'date correct3'
            => [
                [
                    'user_id' => 1,
                    'workloads' => [
                        [
                            'id' => 1,
                            'project_id' => 1,
                            'category_id' => 1,
                            'amount' => 1,
                            'date' => '2018-5-1 01:02:03',
                        ]
                    ],
                ],
                true,
            ],
        ];
    }
}
