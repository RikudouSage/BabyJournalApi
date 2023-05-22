<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Uid\Uuid;

final class OAuthAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    public function __construct(
        private readonly ResourceServer $resourceServer,
        private readonly HttpMessageFactoryInterface $httpMessageFactory,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function start(Request $request, ?AuthenticationException $authException = null): JsonResponse
    {
        return new JsonResponse([
            'error' => $authException?->getMessage() ?? 'Unauthorized',
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request): bool
    {
        return str_starts_with($request->headers->get('Authorization') ?? '', 'Bearer ');
    }

    public function authenticate(Request $request): Passport
    {
        $psr7Request = $this->httpMessageFactory->createRequest($request);
        try {
            $psr7Request = $this->resourceServer->validateAuthenticatedRequest($psr7Request);
        } catch (OAuthServerException $e) {
            throw new CustomUserMessageAuthenticationException($e->getMessage());
        }

        $userIdentifier = $psr7Request->getAttribute('oauth_user_id');
        $accessTokenId = $psr7Request->getAttribute('oauth_access_token_id');
        $scopes = $psr7Request->getAttribute('oauth_scopes', []);
        $oauthClientId = $psr7Request->getAttribute('oauth_client_id');

        $passport = new SelfValidatingPassport(
            new UserBadge($userIdentifier ?? '', function (string $userIdentifier) {
                if (!$userIdentifier) {
                    throw new UserNotFoundException('User not found');
                }

                $user = $this->userRepository->find(Uuid::fromString($userIdentifier));

                return $user ?? throw new UserNotFoundException('User not found');
            }),
        );
        $passport->setAttribute('accessTokenId', $accessTokenId);
        $passport->setAttribute('scopes', $scopes);
        $passport->setAttribute('oauthClientId', $oauthClientId);

        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return $this->start($request, $exception);
    }

    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        $accessTokenId = $passport->getAttribute('accessTokenId');
        $scopes = $passport->getAttribute('scopes');
        $oauthClientId = $passport->getAttribute('oauthClientId');
        $user = $passport->getUser();

        assert(is_string($accessTokenId));
        assert(is_array($scopes));
        assert(is_string($oauthClientId));
        assert($user instanceof User);

        return new OAuthToken($user, $accessTokenId, $oauthClientId, $scopes);
    }
}
