<?php

namespace App\Repository;

use App\Entity\MilestoneActivity;
use App\EntityType\ActivityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MilestoneActivity>
 *
 * @method MilestoneActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method MilestoneActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method MilestoneActivity[]    findAll()
 * @method MilestoneActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MilestoneActivityRepository extends ServiceEntityRepository implements ActivityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MilestoneActivity::class);
    }
}
