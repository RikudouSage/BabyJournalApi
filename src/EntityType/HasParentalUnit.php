<?php

namespace App\EntityType;

use App\Entity\ParentalUnit;

interface HasParentalUnit
{
    public function getParentalUnit(): ?ParentalUnit;

    public function setParentalUnit(?ParentalUnit $parentalUnit): self;
}
