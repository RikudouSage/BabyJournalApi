<?php

namespace App\ApiFilter;

use App\Entity\FeedingActivity;

/**
 * @extends AbstractActivityApiFilter<FeedingActivity>
 */
final class FeedingActivityApiFilter extends AbstractActivityApiFilter
{
    public function getClass(): string
    {
        return FeedingActivity::class;
    }
}
