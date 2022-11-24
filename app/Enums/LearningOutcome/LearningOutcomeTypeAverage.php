<?php declare(strict_types=1);

namespace App\Enums\LearningOutcome;

use BenSampo\Enum\Enum;

/**
 * @method static static Poor()
 * @method static static Average()
 * @method static static Good()
 * @method static static VeryGood()
 * @method static static Excellent()
 */
final class LearningOutcomeTypeAverage extends Enum
{
    const Poor = 1;
    const Average = 2;
    const Good = 3;
    const VeryGood = 4;
    const Excellent= 5;
}
