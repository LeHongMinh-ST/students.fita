<?php

namespace App\Rules;

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Contracts\Validation\Rule;

class TeacherUniqueRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public function __construct(private $id = null)
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(!$value) return true;
        if($this->id) {
            $user = User::query()->where(['teacher_code' =>$value])->where('id', '<>', $this->id)->first();
        } else {
            $user = User::query()->where(['teacher_code' =>$value])->first();
            dd($user);
        }
        return !$user;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Mã giảng viên đã tồn tại!';
    }
}
