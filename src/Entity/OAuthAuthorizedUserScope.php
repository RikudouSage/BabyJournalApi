<?php

namespace App\Entity;

use App\Repository\OAuthAuthorizedUserScopeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OAuthAuthorizedUserScopeRepository::class)]
class OAuthAuthorizedUserScope
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'authorizedOauthScopes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\ManyToOne(inversedBy: 'authorizedUserScopes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OAuthScope $scope = null;

    #[ORM\ManyToOne(inversedBy: 'authorizedUserScopes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OAuthClient $client = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getScope(): ?OAuthScope
    {
        return $this->scope;
    }

    public function setScope(?OAuthScope $scope): self
    {
        $this->scope = $scope;

        return $this;
    }

    public function getClient(): ?OAuthClient
    {
        return $this->client;
    }

    public function setClient(?OAuthClient $client): self
    {
        $this->client = $client;

        return $this;
    }
}
