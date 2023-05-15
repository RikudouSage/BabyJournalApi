<?php

namespace App\Repository;

use App\Entity\DiaperingActivity;
use App\EntityType\ActivityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DiaperingActivity>
 *
 * @implements ActivityRepository<DiaperingActivity>
 *
 * @method DiaperingActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiaperingActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiaperingActivity[]    findAll()
 * @method DiaperingActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiaperingActivityRepository extends ServiceEntityRepository implements ActivityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiaperingActivity::class);
    }

    public function save(DiaperingActivity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DiaperingActivity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DiaperingActivity[] Returns an array of DiaperingActivity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DiaperingActivity
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
