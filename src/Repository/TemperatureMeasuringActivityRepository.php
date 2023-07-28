<?php

namespace App\Repository;

use App\Entity\TemperatureMeasuringActivity;
use App\EntityType\ActivityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TemperatureMeasuringActivity>
 *
 * @method TemperatureMeasuringActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemperatureMeasuringActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemperatureMeasuringActivity[]    findAll()
 * @method TemperatureMeasuringActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemperatureMeasuringActivityRepository extends ServiceEntityRepository implements ActivityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemperatureMeasuringActivity::class);
    }
}
