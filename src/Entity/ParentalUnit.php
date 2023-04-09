<?php

namespace App\Entity;

use App\Repository\ParentalUnitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Rikudou\JsonApiBundle\Attribute\ApiProperty;
use Rikudou\JsonApiBundle\Attribute\ApiResource;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ApiResource]
#[ORM\Entity(repositoryClass: ParentalUnitRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'parental_units')]
class ParentalUnit
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ApiProperty(relation: true)]
    #[ORM\OneToMany(mappedBy: 'parentalUnit', targetEntity: User::class)]
    private Collection $users;

    #[ApiProperty(relation: true)]
    #[ORM\OneToMany(mappedBy: 'parentalUnit', targetEntity: Child::class, orphanRemoval: true)]
    private Collection $children;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $name = null;

    #[ApiProperty]
    #[ORM\Column(type: 'uuid')]
    private ?Uuid $shareCode = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setParentalUnit($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getParentalUnit() === $this) {
                $user->setParentalUnit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Child>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(Child $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParentalUnit($this);
        }

        return $this;
    }

    public function removeChild(Child $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParentalUnit() === $this) {
                $child->setParentalUnit(null);
            }
        }

        return $this;
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

    public function getShareCode(): ?Uuid
    {
        return $this->shareCode;
    }

    public function setShareCode(Uuid $shareCode): self
    {
        $this->shareCode = $shareCode;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function generateShareCode(): void
    {
        if ($this->shareCode === null) {
            $this->setShareCode(Uuid::v4());
        }
    }
}
