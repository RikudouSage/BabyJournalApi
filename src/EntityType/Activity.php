<?php

namespace App\EntityType;

use App\Entity\Child;
use App\Enum\ActivityType;

interface Activity
{
    public function getStartTime(): ?string;
    public function setStartTime(string $startTime): self;
    public function getEndTime(): ?string;
    public function setEndTime(string $endTime): self;
    public function getBreakDuration(): ?string;
    public function setBreakDuration(?string $breakDuration): self;
    public function getAmount(): ?string;
    public function setAmount(string $amount): self;
    public function getChild(): ?Child;
    public function setChild(?Child $child): self;
    public function toJson(): array;
    public function getActivityType(): ActivityType;
}
