<?php

namespace App\Repository;

use App\Entity\FeedingActivity;
use App\EntityType\ActivityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FeedingActivity>
 *
 * @method FeedingActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeedingActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeedingActivity[]    findAll()
 * @method FeedingActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedingActivityRepository extends ServiceEntityRepository implements ActivityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeedingActivity::class);
    }

    public function save(FeedingActivity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FeedingActivity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FeedingActivity[] Returns an array of FeedingActivity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FeedingActivity
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
