<?php

namespace App\Service;

use App\ApiFilter\ApiFilter;
use App\Entity\User;
use App\Repository\ChildRepository;
use Doctrine\ORM\QueryBuilder;
use Rikudou\JsonApiBundle\Service\Filter\AbstractFilteredQueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Uid\Uuid;

final class FilteredQueryBuilder extends AbstractFilteredQueryBuilder
{
    /**
     * @param iterable<ApiFilter> $filters
     */
    public function __construct(
        #[TaggedIterator(tag: 'app.api.entity_filter')]
        private readonly iterable $filters,
        private readonly Security $security,
        private readonly ChildRepository $childRepository,
    ) {
    }

    public function get(string $class, ParameterBag $queryParams, bool $useFilter = true, bool $useSort = true): QueryBuilder
    {
        $builder = parent::get($class, $queryParams, $useFilter, $useSort);
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return $builder->andWhere('1 = 2');
        }

        foreach ($this->filters as $filter) {
            if ($class !== $filter->getClass()) {
                continue;
            }

            $builder = $filter->getQueryBuilder($builder, $user);
        }

        return $builder;
    }
}
