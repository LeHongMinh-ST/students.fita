<?php

namespace App\Http\Requests\Student;

use App\Http\Requests\BaseRequest;

class UpdateStudentRequest extends BaseRequest
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
            'student_code' => 'required|unique:students,student_code,' . $this->id . ',id',
            'full_name' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'student_code' => 'Mã sinh viên',
            'full_name' => 'Tên sinh viên',
        ];
    }
}
