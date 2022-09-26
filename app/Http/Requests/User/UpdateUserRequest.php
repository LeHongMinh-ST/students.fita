<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
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
            'full_name' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_name' => 'tên tài khoản',
            'full_name' => 'họ và tên',
        ];
    }
}
