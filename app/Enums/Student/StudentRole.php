<?php declare(strict_types=1);

namespace App\Enums\Student;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Normal()
 * @method static static ClassMonitor()
 * @method static static Officer()
 */
final class StudentRole extends Enum implements LocalizedEnum
{
    const Normal = 1;//Sinh viên bình thường
    const ClassMonitor = 2;// Lớp trưởng
    const Officer = 3;//Cán bộ lớp
}
