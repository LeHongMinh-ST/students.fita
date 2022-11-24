<?php declare(strict_types=1);

namespace App\Enums\Student;

use BenSampo\Enum\Enum;

/**
 * @method static static NotApproved()
 * @method static static ClassMonitorApproved()
 * @method static static TeacherApproved()
 * @method static static Approved()
 */
final class StudentTempStatus extends Enum
{
    const Pending = 1;
    const ClassMonitorApproved = 2;
    const TeacherApproved = 3;
    const Approved = 4;
}
