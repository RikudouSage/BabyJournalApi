<?php

namespace App\Entity;

use App\EntityType\Activity;
use App\EntityType\HasParentalUnit;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\UserEntityInterface;
use Rikudou\JsonApiBundle\Attribute\ApiProperty;
use Rikudou\JsonApiBundle\Attribute\ApiResource;
use RuntimeException;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ApiResource]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements UserInterface, HasParentalUnit, UserEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * @var array<string>
     */
    #[ORM\Column]
    private array $roles = [];

    #[ApiProperty(relation: true)]
    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?ParentalUnit $parentalUnit = null;

    #[ApiProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $name = null;

    #[ApiProperty(relation: true)]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Child $selectedChild = null;

    /**
     * @var array<class-string<Activity>, string>
     */
    #[ORM\Column(nullable: true)]
    private ?array $newestActivitiesViewed = [];

    /**
     * @var Collection<int, OAuthAccessToken>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: OAuthAccessToken::class, orphanRemoval: true)]
    private Collection $accessTokens;

    /**
     * @var Collection<int, OAuthAuthCode>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: OAuthAuthCode::class, orphanRemoval: true)]
    private Collection $authCodes;

    /**
     * @var Collection<int, OAuthAuthorizedUserScope>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: OAuthAuthorizedUserScope::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $authorizedOauthScopes;

    /**
     * @var Collection<int, OAuthClient>
     */
    #[ORM\ManyToMany(targetEntity: OAuthClient::class, inversedBy: 'users')]
    private Collection $authorizedOauthClients;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $encryptionKey = null;

    public function __construct()
    {
        $this->accessTokens = new ArrayCollection();
        $this->authCodes = new ArrayCollection();
        $this->authorizedOauthScopes = new ArrayCollection();
        $this->authorizedOauthClients = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
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

    public function getParentalUnit(): ?ParentalUnit
    {
        return $this->parentalUnit;
    }

    public function setParentalUnit(?ParentalUnit $parentalUnit): self
    {
        $this->parentalUnit = $parentalUnit;

        return $this;
    }

    public function getSelectedChild(): ?Child
    {
        return $this->selectedChild;
    }

    public function setSelectedChild(?Child $selectedChild): self
    {
        $this->selectedChild = $selectedChild;

        return $this;
    }

    /**
     * @return array<class-string<Activity>, string>|null
     */
    public function getNewestActivitiesViewed(): ?array
    {
        return $this->newestActivitiesViewed;
    }

    /**
     * @param array<class-string<Activity>, string>|null $newestActivitiesViewed
     */
    public function setNewestActivitiesViewed(?array $newestActivitiesViewed): self
    {
        $this->newestActivitiesViewed = $newestActivitiesViewed;

        return $this;
    }

    /**
     * @return Collection<int, OAuthAccessToken>
     */
    public function getAccessTokens(): Collection
    {
        return $this->accessTokens;
    }

    public function addAccessToken(OAuthAccessToken $accessToken): self
    {
        if (!$this->accessTokens->contains($accessToken)) {
            $this->accessTokens->add($accessToken);
            $accessToken->setUser($this);
        }

        return $this;
    }

    public function removeAccessToken(OAuthAccessToken $accessToken): self
    {
        if ($this->accessTokens->removeElement($accessToken)) {
            // set the owning side to null (unless already changed)
            if ($accessToken->getUser() === $this) {
                $accessToken->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OAuthAuthCode>
     */
    public function getAuthCodes(): Collection
    {
        return $this->authCodes;
    }

    public function addAuthCode(OAuthAuthCode $authCode): self
    {
        if (!$this->authCodes->contains($authCode)) {
            $this->authCodes->add($authCode);
            $authCode->setUser($this);
        }

        return $this;
    }

    public function removeAuthCode(OAuthAuthCode $authCode): self
    {
        if ($this->authCodes->removeElement($authCode)) {
            // set the owning side to null (unless already changed)
            if ($authCode->getUser() === $this) {
                $authCode->setUser(null);
            }
        }

        return $this;
    }

    public function getIdentifier(): string
    {
        return $this->getUserIdentifier();
    }

    /**
     * @return array<string>
     */
    public function findAuthorizedScopes(OAuthClient $client): array
    {
        $result = [];
        foreach ($this->authorizedOauthScopes as $authorizedOauthScope) {
            if ($authorizedOauthScope->getClient() !== $client) {
                continue;
            }
            $result[] = $authorizedOauthScope->getScope()?->getIdentifier()
                ?? throw new RuntimeException('Scope without identifier found');
        }

        return array_unique($result);
    }

    public function revokeAllScopes(OAuthClient $client): void
    {
        foreach ($this->authorizedOauthScopes as $authorizedOauthScope) {
            if ($authorizedOauthScope->getClient() !== $client) {
                continue;
            }
            $this->removeAuthorizedOauthScope($authorizedOauthScope);
        }
    }

    /**
     * @return Collection<int, OAuthAuthorizedUserScope>
     */
    public function getAuthorizedOauthScopes(): Collection
    {
        return $this->authorizedOauthScopes;
    }

    public function addAuthorizedOauthScope(OAuthAuthorizedUserScope $authorizedOauthScope): self
    {
        if (!$this->authorizedOauthScopes->contains($authorizedOauthScope)) {
            $this->authorizedOauthScopes->add($authorizedOauthScope);
            $authorizedOauthScope->setOwner($this);
        }

        return $this;
    }

    public function removeAuthorizedOauthScope(OAuthAuthorizedUserScope $authorizedOauthScope): self
    {
        if ($this->authorizedOauthScopes->removeElement($authorizedOauthScope)) {
            // set the owning side to null (unless already changed)
            if ($authorizedOauthScope->getOwner() === $this) {
                $authorizedOauthScope->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OAuthClient>
     */
    public function getAuthorizedOauthClients(): Collection
    {
        return $this->authorizedOauthClients;
    }

    public function addAuthorizedOauthClient(OAuthClient $authorizedOauthClient): self
    {
        if (!$this->authorizedOauthClients->contains($authorizedOauthClient)) {
            $this->authorizedOauthClients->add($authorizedOauthClient);
        }

        return $this;
    }

    public function removeAuthorizedOauthClient(OAuthClient $authorizedOauthClient): self
    {
        $this->authorizedOauthClients->removeElement($authorizedOauthClient);

        return $this;
    }

    public function getEncryptionKey(): ?string
    {
        return $this->encryptionKey;
    }

    public function setEncryptionKey(?string $encryptionKey): self
    {
        $this->encryptionKey = $encryptionKey;

        return $this;
    }

    public function isScopeAuthorized(OAuthScope $scope, OAuthClient $client): bool
    {
        return in_array($scope->getIdentifier(), $this->findAuthorizedScopes($client), true);
    }

    #[ApiProperty(readonly: true, silentFail: true)]
    public function hasApplicationsConnected(): bool
    {
        return count($this->authorizedOauthClients) > 0;
    }

    /**
     * @return array<array{name: string, identifier: string, scopes: array<string>}>
     */
    #[ApiProperty(readonly: true, silentFail: true)]
    public function getApplications(): array
    {
        return array_map(fn (OAuthClient $client) => [
            'name' => (string) $client->getName(),
            'identifier' => (string) $client->getIdentifier(),
            'scopes' => $this->findAuthorizedScopes($client),
        ], [...$this->authorizedOauthClients]);
    }
}
