<?php

namespace App\Repository;

use App\Entity\WeighingActivity;
use App\EntityType\ActivityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeighingActivity>
 *
 * @method WeighingActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeighingActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeighingActivity[]    findAll()
 * @method WeighingActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeighingActivityRepository extends ServiceEntityRepository implements ActivityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeighingActivity::class);
    }
}
