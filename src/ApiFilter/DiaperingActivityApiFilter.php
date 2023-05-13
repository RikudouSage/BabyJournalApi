<?php

namespace App\ApiFilter;

use App\Entity\DiaperingActivity;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;

final class DiaperingActivityApiFilter extends AbstractActivityApiFilter
{
    public function getClass(): string
    {
        return DiaperingActivity::class;
    }
}
