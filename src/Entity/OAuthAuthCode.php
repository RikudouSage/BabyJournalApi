<?php

namespace App\Entity;

use App\EntityType\OAuthEntityWithUserIdentifier;
use App\Repository\OAuthAuthCodeRepository;
use App\Trait\OAuthEntityWithUserIdentifierTrait;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OAuthAuthCodeRepository::class)]
class OAuthAuthCode implements AuthCodeEntityInterface, OAuthEntityWithUserIdentifier
{
    use OAuthEntityWithUserIdentifierTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $redirectUri = null;

    #[ORM\Column(length: 180)]
    private ?string $identifier = null;

    #[ORM\Column]
    private ?DateTimeImmutable $expiryDateTime = null;

    #[ORM\ManyToOne(inversedBy: 'authCodes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'authCodes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OAuthClient $client = null;

    /**
     * @var Collection<int, OAuthScope>
     */
    #[ORM\ManyToMany(targetEntity: OAuthScope::class, inversedBy: 'authCodes')]
    private Collection $scopes;

    #[ORM\Column]
    private bool $revoked = false;

    public function __construct()
    {
        $this->scopes = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getRedirectUri(): ?string
    {
        return $this->redirectUri;
    }

    public function setRedirectUri($uri): self
    {
        if (!is_string($uri)) {
            throw new InvalidArgumentException('The uri must be a string.');
        }
        $this->redirectUri = $uri;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier($identifier): self
    {
        if (!is_string($identifier)) {
            throw new InvalidArgumentException('The identifier must be a string.');
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getClient(): ?OAuthClient
    {
        return $this->client;
    }

    public function setClient(?ClientEntityInterface $client): self
    {
        if (!$client instanceof OAuthClient && $client !== null) {
            throw new InvalidArgumentException('The client must be an instance of ' . OAuthClient::class);
        }
        $this->client = $client;

        return $this;
    }

    /**
     * @return array<int, OAuthScope>
     */
    public function getScopes(): array
    {
        return [...$this->scopes];
    }

    public function addScope(ScopeEntityInterface $scope): self
    {
        if (!$scope instanceof OAuthScope) {
            throw new InvalidArgumentException('The scope must be an instance of ' . OAuthScope::class);
        }
        if (!$this->scopes->contains($scope)) {
            $this->scopes->add($scope);
        }

        return $this;
    }

    public function removeScope(OAuthScope $scope): self
    {
        $this->scopes->removeElement($scope);

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
