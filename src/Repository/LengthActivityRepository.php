<?php

namespace App\Repository;

use App\Entity\LengthActivity;
use App\EntityType\ActivityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LengthActivity>
 *
 * @method LengthActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method LengthActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method LengthActivity[]    findAll()
 * @method LengthActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LengthActivityRepository extends ServiceEntityRepository implements ActivityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LengthActivity::class);
    }
}
