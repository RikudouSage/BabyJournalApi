<?php

namespace App\ApiFilter;

use App\Entity\ParentalUnit;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;

/**
 * @implements ApiFilter<ParentalUnit>
 */
final class ParentalUnitApiFilter implements ApiFilter
{
    public function getClass(): string
    {
        return ParentalUnit::class;
    }

    public function getQueryBuilder(QueryBuilder $builder, User $currentUser): QueryBuilder
    {
        return $builder
            ->andWhere(':currentUser MEMBER OF entity.users')
            ->setParameter('currentUser', $currentUser->getId()?->toBinary())
        ;
    }
}
