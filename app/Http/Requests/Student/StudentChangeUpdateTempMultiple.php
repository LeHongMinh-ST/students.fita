<?php

namespace App\Http\Requests\Student;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class StudentChangeUpdateTempMultiple extends BaseRequest
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
            'request_ids' => 'required',
            'status' => 'required',
        ];
    }
}
