<?php

namespace App\Http\Requests\Report;

use App\Http\Requests\BaseRequest;

class StoreReportRequest extends BaseRequest
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
            'title' => 'required',
            'content' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'tiêu đề',
            'content' => 'nội dung',
        ];
    }
}
