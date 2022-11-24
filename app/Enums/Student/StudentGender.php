<?php declare(strict_types=1);

namespace App\Enums\Student;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Male()
 * @method static static Female()
 * @method static static Other()
 */
final class StudentGender extends Enum implements LocalizedEnum
{
    const Male = 1;
    const Female = 2;
    const Other = 3;
}
