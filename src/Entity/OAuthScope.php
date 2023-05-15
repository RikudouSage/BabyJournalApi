<?php

namespace App\Entity;

use App\Repository\OAuthScopeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OAuthScopeRepository::class)]
class OAuthScope implements ScopeEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 180)]
    private ?string $identifier = null;

    /**
     * @var Collection<int, OAuthAccessToken>
     */
    #[ORM\ManyToMany(targetEntity: OAuthAccessToken::class, mappedBy: 'scopes')]
    private Collection $accessTokens;

    /**
     * @var Collection<int, OAuthAuthCode>
     */
    #[ORM\ManyToMany(targetEntity: OAuthAuthCode::class, mappedBy: 'scopes')]
    private Collection $authCodes;

    /**
     * @var Collection<int, OAuthAuthorizedUserScope>
     */
    #[ORM\OneToMany(mappedBy: 'scope', targetEntity: OAuthAuthorizedUserScope::class, orphanRemoval: true)]
    private Collection $authorizedUserScopes;

    public function __construct()
    {
        $this->accessTokens = new ArrayCollection();
        $this->authCodes = new ArrayCollection();
        $this->authorizedUserScopes = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function jsonSerialize(): ?string
    {
        return $this->getIdentifier();
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
            $accessToken->addScope($this);
        }

        return $this;
    }

    public function removeAccessToken(OAuthAccessToken $accessToken): self
    {
        if ($this->accessTokens->removeElement($accessToken)) {
            $accessToken->removeScope($this);
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
            $authCode->addScope($this);
        }

        return $this;
    }

    public function removeAuthCode(OAuthAuthCode $authCode): self
    {
        if ($this->authCodes->removeElement($authCode)) {
            $authCode->removeScope($this);
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
            $authorizedUserScope->setScope($this);
        }

        return $this;
    }

    public function removeAuthorizedUserScope(OAuthAuthorizedUserScope $authorizedUserScope): self
    {
        if ($this->authorizedUserScopes->removeElement($authorizedUserScope)) {
            // set the owning side to null (unless already changed)
            if ($authorizedUserScope->getScope() === $this) {
                $authorizedUserScope->setScope(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->identifier ?? '';
    }
}
