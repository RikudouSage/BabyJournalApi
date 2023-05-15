<?php

namespace App\Controller;

use App\Entity\OAuthAuthorizedUserScope;
use App\Entity\OAuthClient;
use App\Entity\User;
use App\Enum\Scope;
use App\Repository\OAuthClientRepository;
use App\Repository\OAuthScopeRepository;
use App\Repository\UserRepository;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Nyholm\Psr7\Response as Psr7Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

final class OAuthController extends AbstractController
{
    private const AUTH_SESSION_KEY = 'oauth_auth_request';

    #[Route('/oauth/scopes', name: 'app.oauth.scopes')]
    #[Route('/{_locale}/oauth/scopes', name: 'app.oauth.scopes.localized')]
    public function scopes(TranslatorInterface $translator): JsonResponse
    {
        return new JsonResponse(array_map(static fn (Scope $scope) => [
            'scope' => $scope->value,
            'required' => $scope->isRequired(),
            'description' => $translator->trans($scope->getDescription()),
        ], Scope::cases()));
    }

    #[Route('/oauth/token', name: 'app.oauth.token')]
    public function token(ServerRequestInterface $request, AuthorizationServer $authorizationServer): ResponseInterface
    {
        try {
            return $authorizationServer->respondToAccessTokenRequest($request, new Psr7Response());
        } catch (OAuthServerException $e) {
            return $e->generateHttpResponse(new Psr7Response());
        }
    }

    #[Route('/oauth/authorize', name: 'app.oauth.authorize')]
    public function authorize(
        ServerRequestInterface $request,
        AuthorizationServer $authorizationServer,
        SessionInterface $session,
        #[Autowire('%app.info.frontend_url%')] string $frontendUrl,
    ): RedirectResponse|ResponseInterface {
        try {
            $authRequest = $authorizationServer->validateAuthorizationRequest($request);
        } catch (OAuthServerException $e) {
            return $e->generateHttpResponse(new Psr7Response());
        }

        $session->set(self::AUTH_SESSION_KEY, $authRequest);

        return $this->redirect("{$frontendUrl}/oauth/authorize?client_id={$request->getQueryParams()['client_id']}&scope={$request->getQueryParams()['scope']}");
    }

    #[Route('/oauth/authorize-check', name: 'app.oauth.authorize_check')]
    public function isAuthorized(
        SessionInterface $session,
        UrlGeneratorInterface $urlGenerator,
    ): JsonResponse {
        $user = $this->getUser();
        if ($user === null) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }
        assert($user instanceof User);

        $authRequest = $session->get(self::AUTH_SESSION_KEY);
        if (!$authRequest instanceof AuthorizationRequest) {
            return new JsonResponse([
                'success' => false,
                'error' => "Auth request doesn't exist",
            ], Response::HTTP_NOT_FOUND);
        }
        $client = $authRequest->getClient();
        assert($client instanceof OAuthClient);

        if (
            in_array($client->getIdentifier(), array_map(static fn (OAuthClient $client) => $client->getIdentifier(), [...$user->getAuthorizedOauthClients()]))
            && !count(array_diff(
                array_map(static fn (ScopeEntityInterface $scope) => $scope->getIdentifier(), $authRequest->getScopes()),
                $user->findAuthorizedScopes($client),
            ))
        ) {
            $authRequest->setAuthorizationApproved(true);
            $authRequest->setUser($user);

            $session->set(self::AUTH_SESSION_KEY, $authRequest);

            return new JsonResponse([
                'success' => true,
                'redirectUrl' => $urlGenerator->generate('app.oauth.approve', ['approved' => true], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);
        }

        return new JsonResponse([
            'success' => false,
        ]);
    }

    #[Route('/oauth/client-info/{clientId}', name: 'app.oauth.client_info')]
    public function clientInfo(
        string $clientId,
        OAuthClientRepository $clientRepository,
    ): JsonResponse {
        $client = $clientRepository->findOneBy([
            'identifier' => $clientId,
        ]);
        if ($client === null) {
            return new JsonResponse([
                'error' => 'Client not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'identifier' => $client->getIdentifier(),
            'name' => $client->getName(),
        ]);
    }

    #[Route('/oauth/approve/{userId}/{approved}', name: 'app.oauth.approve')]
    #[Route('/oauth/approve/{userId}/{approved}/{scopes}', name: 'app.oauth.approve.scopes')]
    public function approve(
        SessionInterface $session,
        UserRepository $userRepository,
        OAuthClientRepository $clientRepository,
        AuthorizationServer $authorizationServer,
        OAuthScopeRepository $scopeRepository,
        bool $approved,
        string $userId,
        ?string $scopes = null,
    ): JsonResponse|ResponseInterface {
        $authRequest = $session->get(self::AUTH_SESSION_KEY);
        if (!$authRequest instanceof AuthorizationRequest) {
            return new JsonResponse([
                'error' => 'No user is authorized',
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->find(Uuid::fromString($userId));
        if (!$user instanceof User) {
            return new JsonResponse([
                'error' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $client = $clientRepository->findOneBy([
            'identifier' => $authRequest->getClient()->getIdentifier(),
        ]) ?? throw new RuntimeException('Client not found');

        $authRequest->setAuthorizationApproved($approved);
        $authRequest->setUser($user);
        $authRequest->setClient($client);

        if ($approved) {
            if ($scopes !== null) {
                $scopes = array_map(
                    static fn (string $scope) => $scopeRepository->getScopeEntityByIdentifier($scope)
                        ?? throw new RuntimeException("Scope '{$scope}' not found"),
                    explode(' ', $scopes),
                );
            } else {
                $scopes = array_map(
                    static fn (ScopeEntityInterface $scope)
                        => $scopeRepository->getScopeEntityByIdentifier($scope->getIdentifier())
                            ?? throw new RuntimeException("Scope '{$scope->getIdentifier()}' not found"),
                    $authRequest->getScopes(),
                );
            }
            $authRequest->setScopes($scopes);

            foreach ($scopes as $scope) {
                if (!$user->isScopeAuthorized($scope, $client)) {
                    $user->addAuthorizedOauthScope(
                        (new OAuthAuthorizedUserScope())
                            ->setOwner($user)
                            ->setClient($client)
                            ->setScope($scope),
                    );
                }
            }
            $user->addAuthorizedOauthClient($client);
            $userRepository->save($user, true);
        }
        $session->remove(self::AUTH_SESSION_KEY);

        try {
            return $authorizationServer->completeAuthorizationRequest($authRequest, new Psr7Response());
        } catch (OAuthServerException $e) {
            return $e->generateHttpResponse(new Psr7Response());
        }
    }
}
