<?php

namespace App\Entity;

use App\EntityType\HasParentalUnit;
use App\Repository\ChildRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Rikudou\JsonApiBundle\Attribute\ApiProperty;
use Rikudou\JsonApiBundle\Attribute\ApiResource;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ApiResource]
#[ORM\Entity(repositoryClass: ChildRepository::class)]
#[ORM\Table(name: 'children')]
class Child implements HasParentalUnit
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ApiProperty(relation: true)]
    #[ORM\ManyToOne(inversedBy: 'children')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ParentalUnit $parentalUnit = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $name = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $gender = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $birthDay = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $birthWeight = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $birthHeight = null;

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthDay(): ?string
    {
        return $this->birthDay;
    }

    public function setBirthDay(?string $birthDay): self
    {
        $this->birthDay = $birthDay;

        return $this;
    }

    public function getBirthWeight(): ?string
    {
        return $this->birthWeight;
    }

    public function setBirthWeight(?string $birthWeight): self
    {
        $this->birthWeight = $birthWeight;

        return $this;
    }

    public function getBirthHeight(): ?string
    {
        return $this->birthHeight;
    }

    public function setBirthHeight(?string $birthHeight): self
    {
        $this->birthHeight = $birthHeight;

        return $this;
    }
}
