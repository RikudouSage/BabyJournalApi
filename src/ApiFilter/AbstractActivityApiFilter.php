<?php

namespace App\ApiFilter;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;

/**
 * @template T of object
 *
 * @implements ApiFilter<T>
 */
abstract class AbstractActivityApiFilter implements ApiFilter
{
    public function getQueryBuilder(QueryBuilder $builder, User $currentUser): QueryBuilder
    {
        return $builder
            ->innerJoin('entity.child', 'ch')
            ->innerJoin('ch.parentalUnit', 'pu')
            ->andWhere(':currentUser MEMBER OF pu.users')
            ->setParameter('currentUser', $currentUser->getId()?->toBinary());
    }
}
