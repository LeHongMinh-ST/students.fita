<?php

namespace App\Http\Requests\GeneralClass;

use App\Http\Requests\BaseRequest;

class UpdateGeneralClassRequest extends BaseRequest
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
            'class_code' => 'required|unique:general_classes,class_code,' . $this->id . ',id',
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
