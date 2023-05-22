<?php

namespace App\EntityType;

use App\Entity\User;

interface OAuthEntityWithUserIdentifier
{
    /**
     * @return string
     *
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function getUserIdentifier();

    public function setUser(?User $user): self;
}
