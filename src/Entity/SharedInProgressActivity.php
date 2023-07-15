<?php

namespace App\Entity;

use App\EntityType\HasParentalUnit;
use App\Repository\SharedInProgressActivityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Rikudou\JsonApiBundle\Attribute\ApiProperty;
use Rikudou\JsonApiBundle\Attribute\ApiResource;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ApiResource]
#[ORM\Entity(repositoryClass: SharedInProgressActivityRepository::class)]
#[ORM\Table(name: 'shared_in_progress_activities')]
#[ORM\Index(fields: ['activityType'])]
class SharedInProgressActivity implements HasParentalUnit
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ApiProperty]
    #[ORM\Column(length: 180)]
    private ?string $activityType = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $config = null;

    #[ApiProperty(relation: true, readonly: true, silentFail: true)]
    #[ORM\ManyToOne(inversedBy: 'sharedInProgressActivities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ParentalUnit $parentalUnit = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getActivityType(): ?string
    {
        return $this->activityType;
    }

    public function setActivityType(string $activityType): static
    {
        $this->activityType = $activityType;

        return $this;
    }

    public function getConfig(): ?string
    {
        return $this->config;
    }

    public function setConfig(string $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function getParentalUnit(): ?ParentalUnit
    {
        return $this->parentalUnit;
    }

    public function setParentalUnit(?ParentalUnit $parentalUnit): static
    {
        $this->parentalUnit = $parentalUnit;

        return $this;
    }
}
