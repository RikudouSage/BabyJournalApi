<?php

namespace App\Security;

use App\Enum\Scope;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class OAuthScopeVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === 'OAUTH_SCOPE' && $subject instanceof Scope;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        assert($subject instanceof Scope);

        $scopeRole = "OAUTH_SCOPE_" . strtoupper(preg_replace_callback('@[A-Z]@', fn (array $matches) => '_' . strtoupper($matches[0]), $subject->value));

        return in_array($scopeRole, $token->getRoleNames(), true);
    }
}
