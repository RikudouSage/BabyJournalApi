<?php

namespace App\ApiFilter;

use App\Entity\SleepingActivity;

final class SleepingActivityApiFilter extends AbstractActivityApiFilter
{
    public function getClass(): string
    {
        return SleepingActivity::class;
    }
}
