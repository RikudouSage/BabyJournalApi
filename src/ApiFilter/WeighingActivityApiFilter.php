<?php

namespace App\ApiFilter;

use App\Entity\User;
use App\Entity\WeighingActivity;
use Doctrine\ORM\QueryBuilder;

final class WeighingActivityApiFilter extends AbstractActivityApiFilter
{
    public function getClass(): string
    {
        return WeighingActivity::class;
    }
}
