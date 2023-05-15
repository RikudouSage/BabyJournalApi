<?php

namespace App\ApiFilter;

use App\Entity\PumpingActivity;

/**
 * @extends AbstractActivityApiFilter<PumpingActivity>
 */
final class PumpingActivityApiFilter extends AbstractActivityApiFilter
{
    public function getClass(): string
    {
        return PumpingActivity::class;
    }
}
