<?php

namespace App\Trait;

use App\Entity\User;
use LogicException;
use RuntimeException;

trait OAuthEntityWithUserIdentifierTrait
{
    private ?string $userIdentifier = null;

    public function getUserIdentifier(): string
    {
        return $this->userIdentifier ?? $this->getUser()?->getUserIdentifier() ?? throw new RuntimeException('User identifier cannot be null');
    }

    public function setUserIdentifier($identifier): void
    {
        if (!is_string($identifier) && $identifier !== null) {
            throw new LogicException('Identifier must be a string or null');
        }
        $this->userIdentifier = $identifier;
    }

    abstract public function getUser(): ?User;
}
