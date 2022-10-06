<?php declare(strict_types=1);

namespace App\Enums\Student;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static None()
 * @method static static SonOfWounded()
 * @method static static EspeciallyDifficult()
 * @method static static EthnicMinorityPeopleInTheHighlands()
 * @method static static DisabledPerson()
 */
final class StudentSocialPolicyObject extends Enum implements LocalizedEnum
{
    const None = 1;//Không có
    const SonOfWounded = 2;// Con thương binh liệt sĩ
    const EspeciallyDifficult = 3;// Đặc biệt khó khăn
    const EthnicMinorityPeopleInTheHighlands = 4;// Người dân tộc ít người ở vùng cao.
    const DisabledPerson = 5;// Người dân tộc ít người ở vùng cao.

}
