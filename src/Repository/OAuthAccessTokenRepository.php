<?php

namespace App\Repository;

use App\Entity\OAuthAccessToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use LogicException;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<OAuthAccessToken>
 *
 * @method OAuthAccessToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method OAuthAccessToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method OAuthAccessToken[]    findAll()
 * @method OAuthAccessToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OAuthAccessTokenRepository extends ServiceEntityRepository implements AccessTokenRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly UserRepository $userRepository,
    ) {
        parent::__construct($registry, OAuthAccessToken::class);
    }

    public function save(OAuthAccessToken $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OAuthAccessToken $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null): OAuthAccessToken
    {
        if (!is_string($userIdentifier) && $userIdentifier !== null) {
            throw new LogicException('User identifier must be a string or null');
        }

        $entity = (new OAuthAccessToken())->setClient($clientEntity);
        foreach ($scopes as $scope) {
            $entity->addScope($scope);
        }
        if ($userIdentifier !== null) {
            $entity->setUser($this->userRepository->find(Uuid::fromString($userIdentifier)));
        }

        return $entity;
    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        if (!$accessTokenEntity instanceof OAuthAccessToken) {
            throw new InvalidArgumentException('Access token must ba an instance of ' . OAuthAccessToken::class);
        }
        $this->save($accessTokenEntity, true);
    }

    public function revokeAccessToken($tokenId): void
    {
        $token = $this->findOneBy([
            'identifier' => $tokenId,
        ]);
        if ($token !== null) {
            $token->setRevoked(true);
            $this->save($token, true);
        }
    }

    public function isAccessTokenRevoked($tokenId): bool
    {
        $token = $this->findOneBy([
            'identifier' => $tokenId,
        ]);
        if ($token === null) {
            return false;
        }

        return $token->isRevoked();
    }
}
