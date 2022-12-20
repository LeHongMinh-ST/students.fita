<?php

namespace App\Http\Requests\Report;

use App\Http\Requests\BaseRequest;

class ChangeReportStatusMultipleRequest extends BaseRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'report_ids' => 'required',
            'status' => 'required',
        ];
    }
}
