<?php

namespace App\Repository;

use App\Entity\SharedInProgressActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SharedInProgressActivity>
 *
 * @method SharedInProgressActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SharedInProgressActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SharedInProgressActivity[]    findAll()
 * @method SharedInProgressActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SharedInProgressActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SharedInProgressActivity::class);
    }
}
