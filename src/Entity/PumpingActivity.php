<?php

namespace App\Entity;

use App\EntityType\Activity;
use App\Enum\ActivityType;
use App\Repository\PumpingActivityRepository;
use App\Trait\BasicActivityTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Rikudou\JsonApiBundle\Attribute\ApiProperty;
use Rikudou\JsonApiBundle\Attribute\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: PumpingActivityRepository::class)]
#[ORM\Table(name: 'pumping_activities')]
class PumpingActivity implements Activity
{
    use BasicActivityTrait;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $breast = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $amount = null;

    #[ApiProperty]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $pumpingParent = null;

    public function getActivityType(): ActivityType
    {
        return ActivityType::Pumping;
    }

    protected function getCustomJson(): array
    {
        return [
            'breast' => $this->breast,
            'amount' => $this->amount,
            'parentName' => $this->pumpingParent?->getName(),
        ];
    }

    public function getBreast(): ?string
    {
        return $this->breast;
    }

    public function setBreast(string $breast): self
    {
        $this->breast = $breast;

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

    public function getPumpingParent(): ?User
    {
        return $this->pumpingParent;
    }

    public function setPumpingParent(?User $pumpingParent): self
    {
        $this->pumpingParent = $pumpingParent;

        return $this;
    }
}
