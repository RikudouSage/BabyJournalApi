<?php

namespace App\Repository;

use App\Entity\OAuthRefreshToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

/**
 * @extends ServiceEntityRepository<OAuthRefreshToken>
 *
 * @method OAuthRefreshToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method OAuthRefreshToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method OAuthRefreshToken[]    findAll()
 * @method OAuthRefreshToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OAuthRefreshTokenRepository extends ServiceEntityRepository implements RefreshTokenRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OAuthRefreshToken::class);
    }

    public function save(OAuthRefreshToken $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OAuthRefreshToken $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getNewRefreshToken(): OAuthRefreshToken
    {
        return new OAuthRefreshToken();
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        if (!$refreshTokenEntity instanceof OAuthRefreshToken) {
            throw new InvalidArgumentException('Refresh token must be an instance of ' . OAuthRefreshToken::class);
        }
        $this->save($refreshTokenEntity, true);
    }

    public function revokeRefreshToken($tokenId): void
    {
        $token = $this->findOneBy([
            'identifier' => $tokenId,
        ]);
        if ($token !== null) {
            $token->setRevoked(true);
            $this->save($token, true);
        }
    }

    public function isRefreshTokenRevoked($tokenId): bool
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
