<?php

namespace App\ApiFilter;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;

/**
 * @template T of object
 */
interface ApiFilter
{
    /**
     * @return class-string<T>
     */
    public function getClass(): string;

    public function getQueryBuilder(QueryBuilder $builder, User $currentUser): QueryBuilder;
}
