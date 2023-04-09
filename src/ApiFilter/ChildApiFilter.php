<?php

namespace App\ApiFilter;

use App\Entity\Child;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;

final class ChildApiFilter implements ApiFilter
{

    public function getClass(): string
    {
        return Child::class;
    }

    public function getQueryBuilder(QueryBuilder $builder, User $currentUser): QueryBuilder
    {
        return $builder
            ->innerJoin('entity.parentalUnit', 'pu')
            ->andWhere(':currentUser MEMBER OF pu.users')
            ->setParameter('currentUser', $currentUser->getId()?->toBinary())
        ;
    }
}
