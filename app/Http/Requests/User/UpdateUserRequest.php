<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use App\Repositories\User\UserRepositoryInterface;
use App\Rules\TeacherUniqueRule;


class UpdateUserRequest extends BaseRequest
{

    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'email' => 'required|unique:users,email,' . $this->id . ',id',
            'user_name' => 'required|unique:users,user_name,' . $this->id . ',id',
            'teacher_code' => [
                new TeacherUniqueRule($this->id)
            ],
            'full_name' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_name' => 'tên tài khoản',
            'full_name' => 'họ và tên',
            'teacher_code' => 'mã giảng viên'
        ];
    }
}
