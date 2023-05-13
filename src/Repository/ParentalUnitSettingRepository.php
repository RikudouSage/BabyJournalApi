<?php

namespace App\Repository;

use App\Entity\ParentalUnitSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParentalUnitSetting>
 *
 * @method ParentalUnitSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParentalUnitSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParentalUnitSetting[]    findAll()
 * @method ParentalUnitSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParentalUnitSettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParentalUnitSetting::class);
    }

    public function save(ParentalUnitSetting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ParentalUnitSetting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ParentalUnitSetting[] Returns an array of ParentalUnitSetting objects
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

//    public function findOneBySomeField($value): ?ParentalUnitSetting
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
