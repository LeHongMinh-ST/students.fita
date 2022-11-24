<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use App\Http\Controllers\Api\UserController;
use App\Rules\TeacherUniqueRule;

class StoreUserRequest extends BaseRequest
{
    public function __construct(private UserController $userController)
    {
    }
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
    public function rules(): array
    {
        return [
            'email' => 'required|unique:users',
            'password' => 'required',
            'full_name' => 'required',
            'user_name' => 'required|unique:users',
            'teacher_code' => [
                new TeacherUniqueRule()
            ]
        ];
    }

    public function attributes(): array
    {
        return [
            'password' => 'mật khẩu',
            'user_name' => 'tên tài khoản',
            'full_name' => 'họ và tên',
            'teacher_code' => 'Mã giảng viên'
        ];
    }
}
