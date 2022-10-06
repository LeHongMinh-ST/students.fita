<?php declare(strict_types=1);

namespace App\Enums\Student;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static FormalUniversity()
 * @method static static College()
 */
final class StudentTrainingType extends Enum implements LocalizedEnum
{
    const FormalUniversity = 1;
    const College = 2;
}
