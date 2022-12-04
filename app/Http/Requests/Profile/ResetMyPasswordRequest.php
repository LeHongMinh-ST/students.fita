<?php

namespace App\Http\Requests\Profile;

use App\Http\Requests\BaseRequest;

class ResetMyPasswordRequest extends BaseRequest
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

    public function rules()
    {
        return [
            'password' => 'required|confirmed|min:6',
            'password_old' => 'required',
            'password_confirmation' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'password' => 'mật khẩu',
            'password_old' => 'mật khẩu cũ',
            'password_confirmation' => 'xác nhận mật khẩu',
        ];
    }
}
