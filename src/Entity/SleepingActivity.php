<?php

namespace App\Entity;

use App\EntityType\Activity;
use App\Enum\ActivityType;
use App\Repository\SleepingActivityRepository;
use App\Trait\BasicActivityTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SleepingActivityRepository::class)]
#[ORM\Table(name: 'sleeping_activities')]
class SleepingActivity implements Activity
{
    use BasicActivityTrait;

    public function getActivityType(): ActivityType
    {
        return ActivityType::Sleeping;
    }

    protected function getCustomJson(): array
    {
        return [];
    }
}
