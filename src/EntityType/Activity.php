<?php

namespace App\EntityType;

use App\Entity\Child;
use App\Enum\ActivityType;
use BackedEnum;
use Symfony\Component\Uid\Uuid;

interface Activity
{
    public function getId(): ?Uuid;

    public function getStartTime(): ?string;

    public function setStartTime(string $startTime): self;

    public function getEndTime(): ?string;

    public function setEndTime(string $endTime): self;

    public function getBreakDuration(): ?string;

    public function setBreakDuration(?string $breakDuration): self;

    public function getChild(): ?Child;

    public function setChild(?Child $child): self;

    /**
     * @return array<string, string|null|BackedEnum>
     */
    public function toJson(): array;

    public function getActivityType(): ActivityType;
}
