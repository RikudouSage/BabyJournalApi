<?php

namespace App\ApiFilter;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;

/**
 * @implements ApiFilter<User>
 */
final class UserApiFilter implements ApiFilter
{
    public function getClass(): string
    {
        return User::class;
    }

    public function getQueryBuilder(QueryBuilder $builder, User $currentUser): QueryBuilder
    {
        return $builder
            ->innerJoin('entity.parentalUnit', 'pu')
            ->andWhere(':currentUser MEMBER OF pu.users')
            ->setParameter('currentUser', $currentUser->getId()?->toBinary());
    }
}
