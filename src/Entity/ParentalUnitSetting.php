<?php

namespace App\Entity;

use App\Enum\ParentalUnitSetting as ParentalUnitSettingEnum;
use App\Repository\ParentalUnitSettingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Rikudou\JsonApiBundle\Attribute\ApiResource;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ApiResource]
#[ORM\Entity(repositoryClass: ParentalUnitSettingRepository::class)]
#[ORM\Table(name: 'parental_unit_settings')]
#[ORM\Index(fields: ['setting'])]
class ParentalUnitSetting
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 180, enumType: ParentalUnitSettingEnum::class)]
    private ?ParentalUnitSettingEnum $setting = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'settings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ParentalUnit $parentalUnit = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getSetting(): ?ParentalUnitSettingEnum
    {
        return $this->setting;
    }

    public function setSetting(ParentalUnitSettingEnum $setting): self
    {
        $this->setting = $setting;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
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
}
