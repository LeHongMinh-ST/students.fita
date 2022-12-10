<?php declare(strict_types=1);

namespace App\Enums\Report;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ReportSubject extends Enum implements LocalizedEnum
{
    const Study = 1;
    const Diligence = 2;
    const SchoolUnionActivities = 3;
    const Reward = 4;
    const Other = 5;
}
