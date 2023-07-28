<?php

namespace App\Entity;

use App\EntityType\Activity;
use App\Enum\ActivityType;
use App\Repository\TemperatureMeasuringActivityRepository;
use App\Trait\BasicActivityTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Rikudou\JsonApiBundle\Attribute\ApiProperty;
use Rikudou\JsonApiBundle\Attribute\ApiResource;

#[ApiResource]
#[ORM\Table(name: 'temperature_measuring_activities')]
#[ORM\Entity(repositoryClass: TemperatureMeasuringActivityRepository::class)]
class TemperatureMeasuringActivity implements Activity
{
    use BasicActivityTrait;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $temperature = null;

    public function getActivityType(): ActivityType
    {
        return ActivityType::Temperature;
    }

    protected function getCustomJson(): array
    {
        return [
            'temperature' => $this->temperature,
        ];
    }

    public function getTemperature(): ?string
    {
        return $this->temperature;
    }

    public function setTemperature(string $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }
}
