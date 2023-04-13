<?php

namespace App\Entity;

use App\EntityType\Activity;
use App\EntityType\HasParentalUnit;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Rikudou\JsonApiBundle\Attribute\ApiProperty;
use Rikudou\JsonApiBundle\Attribute\ApiResource;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ApiResource]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements UserInterface, HasParentalUnit
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\Column]
    private array $roles = [];

    #[ApiProperty(relation: true)]
    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?ParentalUnit $parentalUnit = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $name = null;

    #[ApiProperty(relation: true)]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Child $selectedChild = null;

    /**
     * @var array<class-string<Activity>, string>
     */
    #[ORM\Column(nullable: true)]
    private ?array $newestActivitiesViewed = [];

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    #[ApiProperty(silentFail: true)]
    public function getDisplayName(): string
    {
        return (string) ($this->getName() ?? $this->getId());
    }

    public function getParentalUnit(): ?ParentalUnit
    {
        return $this->parentalUnit;
    }

    public function setParentalUnit(?ParentalUnit $parentalUnit): self
    {
        $this->parentalUnit = $parentalUnit;

        return $this;
    }

    public function getSelectedChild(): ?Child
    {
        return $this->selectedChild;
    }

    public function setSelectedChild(?Child $selectedChild): self
    {
        $this->selectedChild = $selectedChild;

        return $this;
    }

    public function getNewestActivitiesViewed(): ?array
    {
        return $this->newestActivitiesViewed;
    }

    public function setNewestActivitiesViewed(?array $newestActivitiesViewed): self
    {
        $this->newestActivitiesViewed = $newestActivitiesViewed;

        return $this;
    }
}
