<?php

namespace App\Entity;

use App\Repository\OAuthRefreshTokenRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OAuthRefreshTokenRepository::class)]
class OAuthRefreshToken implements RefreshTokenEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 180)]
    private ?string $identifier = null;

    #[ORM\Column]
    private ?DateTimeImmutable $expiryDateTime = null;

    #[ORM\ManyToOne(inversedBy: 'refreshTokens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OAuthAccessToken $accessToken = null;

    #[ORM\Column]
    private bool $revoked = false;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier($identifier): self
    {
        if (!is_string($identifier)) {
            throw new InvalidArgumentException('Identifier must be a string');
        }
        $this->identifier = $identifier;

        return $this;
    }

    public function getExpiryDateTime(): ?DateTimeImmutable
    {
        return $this->expiryDateTime;
    }

    public function setExpiryDateTime(DateTimeImmutable $dateTime): self
    {
        $this->expiryDateTime = $dateTime;

        return $this;
    }

    public function getAccessToken(): ?OAuthAccessToken
    {
        return $this->accessToken;
    }

    public function setAccessToken(?AccessTokenEntityInterface $accessToken): self
    {
        if (!$accessToken instanceof OAuthAccessToken && $accessToken !== null) {
            throw new InvalidArgumentException('Access token must be an instance of ' . OAuthAccessToken::class);
        }
        $this->accessToken = $accessToken;

        return $this;
    }

    public function isRevoked(): bool
    {
        return $this->revoked;
    }

    public function setRevoked(bool $revoked): self
    {
        $this->revoked = $revoked;

        return $this;
    }
}
