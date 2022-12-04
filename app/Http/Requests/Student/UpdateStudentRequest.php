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
            'class_id' => 'lớp',
            'role' => 'vai trò',
            'student_code' => 'mã sinh viên',
            'status' => 'tình trạng sinh viên',
            'gender' => 'giới tính',
            'dob' => 'ngày sinh',
        ];
    }
}
