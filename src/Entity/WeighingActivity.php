<?php

namespace App\Entity;

use App\EntityType\Activity;
use App\Enum\ActivityType;
use App\Repository\WeighingActivityRepository;
use App\Trait\BasicActivityTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Rikudou\JsonApiBundle\Attribute\ApiProperty;
use Rikudou\JsonApiBundle\Attribute\ApiResource;

#[ApiResource]
#[ORM\Table(name: 'weighing_activities')]
#[ORM\Entity(repositoryClass: WeighingActivityRepository::class)]
class WeighingActivity implements Activity
{
    use BasicActivityTrait;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $weight = null;

    public function getActivityType(): ActivityType
    {
        return ActivityType::Weighing;
    }

    protected function getCustomJson(): array
    {
        return [
            'weight' => $this->weight,
        ];
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): static
    {
        $this->weight = $weight;

        return $this;
    }
}
