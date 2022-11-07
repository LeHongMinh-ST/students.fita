<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class StoreUserRequest extends BaseRequest
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
    public function rules(): array
    {
        return [
            'email' => 'required|unique:users',
            'password' => 'required',
            'full_name' => 'required',
            'user_name' => 'required|unique:users',
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
