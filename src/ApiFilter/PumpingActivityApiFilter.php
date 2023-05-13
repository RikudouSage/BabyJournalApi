<?php

namespace App\ApiFilter;

use App\Entity\PumpingActivity;

final class PumpingActivityApiFilter extends AbstractActivityApiFilter
{

    public function getClass(): string
    {
        return PumpingActivity::class;
    }
}
