<?php declare(strict_types=1);

namespace App\Enums\Report;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class ReportStatus extends Enum implements LocalizedEnum
{
    const Pending = 1;
    const Seen = 2;
    const Approved = 3;
}
