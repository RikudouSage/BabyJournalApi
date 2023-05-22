<?php

namespace App\Entity;

use App\EntityType\Activity;
use App\Enum\ActivityType;
use App\Repository\SleepingActivityRepository;
use App\Trait\BasicActivityTrait;
use BackedEnum;
use Doctrine\ORM\Mapping as ORM;
use Rikudou\JsonApiBundle\Attribute\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: SleepingActivityRepository::class)]
#[ORM\Table(name: 'sleeping_activities')]
class SleepingActivity implements Activity
{
    use BasicActivityTrait;

    public function getActivityType(): ActivityType
    {
        return ActivityType::Sleeping;
    }

    /**
     * @return array<string, string|null|BackedEnum>
     */
    protected function getCustomJson(): array
    {
        return [];
    }
}
