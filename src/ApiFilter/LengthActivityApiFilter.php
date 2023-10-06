<?php

namespace App\ApiFilter;

use App\Entity\LengthActivity;

final class LengthActivityApiFilter extends AbstractActivityApiFilter
{
    public function getClass(): string
    {
        return LengthActivity::class;
    }
}
