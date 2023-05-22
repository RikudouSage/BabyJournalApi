<?php

namespace App\EntityType;

use Doctrine\Persistence\ObjectRepository;

/**
 * @template-covariant T of object
 *
 * @extends ObjectRepository<T>
 */
interface ActivityRepository extends ObjectRepository
{
}
