<?php

namespace App\Repository;

use App\Entity\SleepingActivity;
use App\EntityType\ActivityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SleepingActivity>
 *
 * @method SleepingActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SleepingActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SleepingActivity[]    findAll()
 * @method SleepingActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SleepingActivityRepository extends ServiceEntityRepository implements ActivityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SleepingActivity::class);
    }

    public function save(SleepingActivity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SleepingActivity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
