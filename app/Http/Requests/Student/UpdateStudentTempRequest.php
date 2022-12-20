<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentTempRequest extends FormRequest
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
            'student_code' => 'required',
            'full_name' => 'required',
            'role' => 'required',
            'status' => 'required',
            'gender' => 'required',
            'school_year' => 'required',
            'dob' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'full_name' => 'họ và tên',
            'school_year' => 'niên khóa',
            'role' => 'vai trò',
            'student_code' => 'mã sinh viên',
            'status' => 'tình trạng sinh viên',
            'gender' => 'giới tính',
            'dob' => 'ngày sinh',
        ];
    }
}
