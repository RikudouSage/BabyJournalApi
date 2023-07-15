<?php

namespace App\ApiFilter;

use App\Entity\SharedInProgressActivity;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;

final class SharedInProgressActivityFilter implements ApiFilter
{

    public function getClass(): string
    {
        return SharedInProgressActivity::class;
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
