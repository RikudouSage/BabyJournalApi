<?php

namespace App\Repository;

use App\Entity\PumpingActivity;
use App\EntityType\ActivityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PumpingActivity>
 *
 * @implements ActivityRepository<PumpingActivity>
 *
 * @method PumpingActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method PumpingActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method PumpingActivity[]    findAll()
 * @method PumpingActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PumpingActivityRepository extends ServiceEntityRepository implements ActivityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PumpingActivity::class);
    }

    public function save(PumpingActivity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PumpingActivity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PumpingActivity[] Returns an array of PumpingActivity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PumpingActivity
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
