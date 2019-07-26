<?php

namespace App\Http\Requests\ReportComment;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateReportComment extends FormRequest
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
                'present',
                'nullable',
                'integer',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
            'report_comment' => [
                'required',
                'string',
            ],
            'report_opinion' => [
                'required',
                'string',
            ],
            'date' => [
                'required',
                'date_format:Y-m-d',
            ],
        ];
    }
}
