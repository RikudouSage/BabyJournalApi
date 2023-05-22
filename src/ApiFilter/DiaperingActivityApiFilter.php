<?php

namespace App\ApiFilter;

use App\Entity\DiaperingActivity;

/**
 * @extends AbstractActivityApiFilter<DiaperingActivity>
 */
final class DiaperingActivityApiFilter extends AbstractActivityApiFilter
{
    public function getClass(): string
    {
        return DiaperingActivity::class;
    }
}
