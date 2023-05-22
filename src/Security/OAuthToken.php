<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

final class OAuthToken extends AbstractToken
{
    public function __construct(
        User $user,
        string $accessTokenId,
        string $oauthClientId,
        array $scopes,
    ) {
        $this->setAttribute('access_token_id', $accessTokenId);
        $this->setAttribute('oauth_client_id', $oauthClientId);
        $this->setAttribute('scopes', $scopes);

        $scopeRoles = array_map(function (string $scope): string {
            return "OAUTH_SCOPE_" . strtoupper(preg_replace_callback('@[A-Z]@', fn (array $matches) => '_' . strtoupper($matches[0]), $scope));
        }, $scopes);

        $roles = array_merge($scopeRoles, $user->getRoles());
        $this->setUser($user);

        parent::__construct($roles);
    }
}
