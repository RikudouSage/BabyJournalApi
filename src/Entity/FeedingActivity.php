<?php

namespace App\Entity;

use App\EntityType\Activity;
use App\Enum\ActivityType;
use App\Repository\FeedingActivityRepository;
use App\Trait\BasicActivityTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Rikudou\JsonApiBundle\Attribute\ApiProperty;
use Rikudou\JsonApiBundle\Attribute\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: FeedingActivityRepository::class)]
#[ORM\Table(name: 'feeding_activities')]
class FeedingActivity implements Activity
{
    use BasicActivityTrait;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $type = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $amount = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bottleContentType = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $breast = null;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(?string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getBottleContentType(): ?string
    {
        return $this->bottleContentType;
    }

    public function setBottleContentType(?string $bottleContentType): self
    {
        $this->bottleContentType = $bottleContentType;

        return $this;
    }

    public function toJson(): array
    {
        return \Rikudou\ArrayMergeRecursive\array_merge_recursive($this->getBaseJson(), [
            'type' => $this->type,
            'amount' => $this->amount,
            'bottleContentType' => $this->bottleContentType,
            'breast' => $this->breast,
        ]);
    }

    public function getActivityType(): ActivityType
    {
        if ($this->bottleContentType === null && $this->breast === null) {
            return ActivityType::FeedingSolid;
        }
        return $this->bottleContentType !== null
            ? ActivityType::FeedingBottle
            : ActivityType::FeedingBreast;
    }

    public function getBreast(): ?string
    {
        return $this->breast;
    }

    public function setBreast(?string $breast): self
    {
        $this->breast = $breast;

        return $this;
    }
}
