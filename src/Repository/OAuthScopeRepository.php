<?php

namespace App\Repository;

use App\Entity\OAuthClient;
use App\Entity\OAuthScope;
use App\Enum\Scope;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Symfony\Component\Uid\Uuid;
use RuntimeException;

/**
 * @extends ServiceEntityRepository<OAuthScope>
 *
 * @method OAuthScope|null find($id, $lockMode = null, $lockVersion = null)
 * @method OAuthScope|null findOneBy(array $criteria, array $orderBy = null)
 * @method OAuthScope[]    findAll()
 * @method OAuthScope[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OAuthScopeRepository extends ServiceEntityRepository implements ScopeRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly UserRepository $userRepository,
    ) {
        parent::__construct($registry, OAuthScope::class);
    }

    public function save(OAuthScope $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OAuthScope $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getScopeEntityByIdentifier($identifier): ?OAuthScope
    {
        $scopeEnum = Scope::tryFrom($identifier);
        if ($scopeEnum === null) {
            return null;
        }
        $scope = $this->findOneBy([
            'identifier' => $identifier,
        ]);
        if ($scope === null) {
            $scope = (new OAuthScope())
                ->setIdentifier($identifier);
            $this->save($scope, true);
        }

        return $scope;
    }

    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null): array
    {
        if (!$clientEntity instanceof OAuthClient) {
            throw new RuntimeException('Clients must be instance of ' . OAuthClient::class);
        }
        if ($userIdentifier === null) {
            return [];
        }

        $user = $this->userRepository->find(Uuid::fromString($userIdentifier));
        if ($user === null) {
            return [];
        }

        foreach ($scopes as $key => $scope) {
            if (!in_array($scope->getIdentifier(), $user->findAuthorizedScopes($clientEntity), true)) {
                unset($scopes[$key]);
            }
        }

        return array_values($scopes);
    }
}
