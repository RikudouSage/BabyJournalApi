<?php

namespace App\Entity;

use App\EntityType\Activity;
use App\Enum\ActivityType;
use App\Repository\DiaperingActivityRepository;
use App\Trait\BasicActivityTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Rikudou\JsonApiBundle\Attribute\ApiProperty;
use Rikudou\JsonApiBundle\Attribute\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: DiaperingActivityRepository::class)]
#[ORM\Table(name: 'diapering_activities')]
class DiaperingActivity implements Activity
{
    use BasicActivityTrait;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $wet = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $poopy = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $quantity = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $poopColor = null;

    public function getCustomJson(): array
    {
        return [
            'wet' => $this->wet,
            'poopy' => $this->poopy,
            'quantity' => $this->quantity,
            'poopColor' => $this->poopColor,
        ];
    }

    public function getActivityType(): ActivityType
    {
        return ActivityType::Diapering;
    }

    public function isWet(): ?string
    {
        return $this->wet;
    }

    public function setWet(?string $wet): self
    {
        $this->wet = $wet;

        return $this;
    }

    public function isPoopy(): ?string
    {
        return $this->poopy;
    }

    public function setPoopy(?string $poopy): self
    {
        $this->poopy = $poopy;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(?string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPoopColor(): ?string
    {
        return $this->poopColor;
    }

    public function setPoopColor(?string $poopColor): self
    {
        $this->poopColor = $poopColor;

        return $this;
    }
}
