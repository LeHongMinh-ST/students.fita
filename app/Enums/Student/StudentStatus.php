<?php declare(strict_types=1);

namespace App\Enums\Student;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Studying()
 * @method static static Graduated()
 * @method static static Suspended()
 * @method static static ForcedToQuitSchool()
 * @method static static Reserve()
 */
final class StudentStatus extends Enum implements LocalizedEnum
{
    const Studying = 1;// Đang theo học
    const Graduated = 2;// Đã tốt nghiệp
    const Suspended = 3;// Tạm đình chỉ
    const ForcedToQuitSchool = 4;// Buộc thôi học
    const Reserve = 5;// Bảo lưu
}
