<?php

namespace App\Entity;

use App\EntityType\Activity;
use App\Enum\ActivityType;
use App\Repository\LengthActivityRepository;
use App\Trait\BasicActivityTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Rikudou\JsonApiBundle\Attribute\ApiProperty;
use Rikudou\JsonApiBundle\Attribute\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: LengthActivityRepository::class)]
#[ORM\Table(name: 'length_activities')]
class LengthActivity implements Activity
{
    use BasicActivityTrait;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $length = null;

    public function getActivityType(): ActivityType
    {
        return ActivityType::Length;
    }

    protected function getCustomJson(): array
    {
        return [
            'length' => $this->length,
        ];
    }

    public function getLength(): ?string
    {
        return $this->length;
    }

    public function setLength(string $length): static
    {
        $this->length = $length;

        return $this;
    }
}
