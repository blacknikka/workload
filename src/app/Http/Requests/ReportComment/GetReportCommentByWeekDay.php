<?php

namespace App\Http\Requests\ReportComment;

use Illuminate\Foundation\Http\FormRequest;

class GetReportCommentByWeekDay extends FormRequest
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
            // user id
            'id' => [
                'required',
                'integer',
            ],
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
