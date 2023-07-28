<?php

namespace App\ApiFilter;

use App\Entity\TemperatureMeasuringActivity;

final class TemperatureMeasuringActivityApiFilter extends AbstractActivityApiFilter
{
    public function getClass(): string
    {
        return TemperatureMeasuringActivity::class;
    }
}
