<?php

namespace App\Entity;

use App\EntityType\Activity;
use App\Enum\ActivityType;
use App\Repository\MilestoneActivityRepository;
use App\Trait\BasicActivityTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Rikudou\JsonApiBundle\Attribute\ApiProperty;
use Rikudou\JsonApiBundle\Attribute\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: MilestoneActivityRepository::class)]
#[ORM\Table(name: 'milestone_activities')]
class MilestoneActivity implements Activity
{
    use BasicActivityTrait;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $milestoneName = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $predefinedMilestone = null;

    public function getActivityType(): ActivityType
    {
        return ActivityType::Milestone;
    }

    protected function getCustomJson(): array
    {
        return [
            'milestoneName' => $this->milestoneName,
            'predefinedMilestone' => $this->predefinedMilestone,
        ];
    }

    public function getMilestoneName(): ?string
    {
        return $this->milestoneName;
    }

    public function setMilestoneName(?string $milestoneName): static
    {
        $this->milestoneName = $milestoneName;

        return $this;
    }

    public function getPredefinedMilestone(): ?string
    {
        return $this->predefinedMilestone;
    }

    public function setPredefinedMilestone(?string $predefinedMilestone): static
    {
        $this->predefinedMilestone = $predefinedMilestone;

        return $this;
    }
}
