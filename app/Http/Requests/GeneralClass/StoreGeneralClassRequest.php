<?php

namespace App\Http\Requests\GeneralClass;

use Illuminate\Foundation\Http\FormRequest;

class StoreGeneralClassRequest extends FormRequest
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
            'name' => 'required',
            'class_code' => 'required|unique',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'tên lớp',
            'class_code' => 'mã lớp',
        ];
    }
}
