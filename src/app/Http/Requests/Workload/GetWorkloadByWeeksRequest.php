<?php

namespace App\Http\Requests\Workload;

use Illuminate\Foundation\Http\FormRequest;

class GetWorkloadByWeeksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => [
                'required',
                'integer',
            ],

            // この日付を含む週（日曜日〜土曜日）の情報が対象となる
            'week' => [
                'required',
                'date_format:Y-m-d',
            ],
        ];
    }

    /**
     * ルートパラメータをバリデーションするため、バリデーションデータをマージする
     *
     * @return array
     */
    protected function validationData(): array
    {
        return array_merge(
            $this->request->all(),
            [
                'id' => $this->route('id'),
                'week' => $this->route('week'),
            ]
        );
    }

}
