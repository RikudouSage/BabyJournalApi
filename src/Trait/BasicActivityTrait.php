<?php

namespace App\Trait;

use App\Entity\Child;
use BackedEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Rikudou\JsonApiBundle\Attribute\ApiProperty;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

use function Rikudou\ArrayMergeRecursive\array_merge_recursive;

trait BasicActivityTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $startTime = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $endTime = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $breakDuration = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $note = null;

    #[ApiProperty(relation: true)]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Child $child = null;

    /**
     * @return array<string, string|null|BackedEnum>
     */
    abstract protected function getCustomJson(): array;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    public function setStartTime(string $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?string
    {
        return $this->endTime;
    }

    public function setEndTime(?string $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getBreakDuration(): ?string
    {
        return $this->breakDuration;
    }

    public function setBreakDuration(?string $breakDuration): self
    {
        $this->breakDuration = $breakDuration;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getChild(): ?Child
    {
        return $this->child;
    }

    public function setChild(?Child $child): self
    {
        $this->child = $child;

        return $this;
    }

    /**
     * @return array<string, string|null|BackedEnum>
     */
    public function toJson(): array
    {
        return array_merge_recursive($this->getBaseJson(), $this->getCustomJson());
    }

    /**
     * @return array<string, string|null|BackedEnum>
     */
    private function getBaseJson(): array
    {
        return [
            'id' => (string) $this->id,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'note' => $this->note,
            'activityType' => $this->getActivityType(),
            'childName' => $this->child?->getName(),
        ];
    }
}
