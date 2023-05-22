<?php

namespace App\Entity;

use App\Repository\OAuthClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OAuthClientRepository::class)]
#[ORM\Index(fields: ['identifier'])]
class OAuthClient implements ClientEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * @var Collection<int, OAuthAccessToken>
     */
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: OAuthAccessToken::class, orphanRemoval: true)]
    private Collection $accessTokens;

    #[ORM\Column(length: 180)]
    private ?string $identifier = null;

    #[ORM\Column(length: 180)]
    private ?string $name = null;

    #[ORM\Column]
    private bool $confidential = true;

    /**
     * @var Collection<int, OAuthAuthCode>
     */
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: OAuthAuthCode::class, orphanRemoval: true)]
    private Collection $authCodes;

    /**
     * @var Collection<int, OAuthAuthorizedUserScope>
     */
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: OAuthAuthorizedUserScope::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $authorizedUserScopes;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'authorizedOauthClients')]
    private Collection $users;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $secret = null;

    /**
     * @var array<string>|null
     */
    #[ORM\Column(nullable: true)]
    private ?array $allowedRedirectUris = [];

    public function __construct()
    {
        $this->accessTokens = new ArrayCollection();
        $this->authCodes = new ArrayCollection();
        $this->authorizedUserScopes = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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
            $accessToken->setClient($this);
        }

        return $this;
    }

    public function removeAccessToken(OAuthAccessToken $accessToken): self
    {
        if ($this->accessTokens->removeElement($accessToken)) {
            // set the owning side to null (unless already changed)
            if ($accessToken->getClient() === $this) {
                $accessToken->setClient(null);
            }
        }

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isConfidential(): bool
    {
        return $this->confidential;
    }

    public function setConfidential(bool $confidential): self
    {
        $this->confidential = $confidential;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getRedirectUri(): array
    {
        return $this->getAllowedRedirectUris() ?? [];
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
            $authCode->setClient($this);
        }

        return $this;
    }

    public function removeAuthCode(OAuthAuthCode $authCode): self
    {
        if ($this->authCodes->removeElement($authCode)) {
            // set the owning side to null (unless already changed)
            if ($authCode->getClient() === $this) {
                $authCode->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OAuthAuthorizedUserScope>
     */
    public function getAuthorizedUserScopes(): Collection
    {
        return $this->authorizedUserScopes;
    }

    public function addAuthorizedUserScope(OAuthAuthorizedUserScope $authorizedUserScope): self
    {
        if (!$this->authorizedUserScopes->contains($authorizedUserScope)) {
            $this->authorizedUserScopes->add($authorizedUserScope);
            $authorizedUserScope->setClient($this);
        }

        return $this;
    }

    public function removeAuthorizedUserScope(OAuthAuthorizedUserScope $authorizedUserScope): self
    {
        if ($this->authorizedUserScopes->removeElement($authorizedUserScope)) {
            // set the owning side to null (unless already changed)
            if ($authorizedUserScope->getClient() === $this) {
                $authorizedUserScope->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addAuthorizedOauthClient($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeAuthorizedOauthClient($this);
        }

        return $this;
    }

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function setSecret(?string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @return array<string>|null
     */
    public function getAllowedRedirectUris(): ?array
    {
        return $this->allowedRedirectUris;
    }

    /**
     * @param array<string>|null $allowedRedirectUris
     */
    public function setAllowedRedirectUris(?array $allowedRedirectUris): self
    {
        $this->allowedRedirectUris = $allowedRedirectUris;

        return $this;
    }
}
