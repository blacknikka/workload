<?php

namespace App\Http\Requests\Workload;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkloadRequest extends FormRequest
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
            'user_id' => [
                'required',
                'integer',
            ],
            'workloads' => [
                'required',
                'array',
            ],
            'workloads.*.id' => [
                'required',
                'integer',
            ],
            'workloads.*.project_id' => [
                'required',
                'integer',
            ],
            'workloads.*.category_id' => [
                'required',
                'integer',
            ],
            'workloads.*.amount' => [
                'required',
                'numeric',
                'min:0',
            ],
            'workloads.*.date' => [
                'required',
                'date',
            ],
        ];
    }
}
