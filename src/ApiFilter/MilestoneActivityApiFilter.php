<?php

namespace App\ApiFilter;

use App\Entity\MilestoneActivity;

final class MilestoneActivityApiFilter extends AbstractActivityApiFilter
{
    public function getClass(): string
    {
        return MilestoneActivity::class;
    }
}
