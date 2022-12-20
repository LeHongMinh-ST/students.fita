<?php declare(strict_types=1);

namespace App\Enums\Report;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ReportStatusApproved extends Enum implements LocalizedEnum
{
    const Pe = 1;
    const OptionThree = 2;
}
