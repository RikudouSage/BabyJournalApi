<?php

namespace App\Repository;

use App\Entity\OAuthAuthCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

/**
 * @extends ServiceEntityRepository<OAuthAuthCode>
 *
 * @method OAuthAuthCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method OAuthAuthCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method OAuthAuthCode[]    findAll()
 * @method OAuthAuthCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OAuthAuthCodeRepository extends ServiceEntityRepository implements AuthCodeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OAuthAuthCode::class);
    }

    public function save(OAuthAuthCode $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OAuthAuthCode $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getNewAuthCode(): OAuthAuthCode
    {
        return new OAuthAuthCode();
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void
    {
        if (!$authCodeEntity instanceof OAuthAuthCode) {
            throw new InvalidArgumentException('The auth code must be an instance of ' . OAuthAuthCode::class);
        }
        $this->save($authCodeEntity, true);
    }

    public function revokeAuthCode($codeId): void
    {
        $code = $this->findOneBy([
            'identifier' => $codeId,
        ]);
        if ($code !== null) {
            $code->setRevoked(true);
            $this->save($code, true);
        }
    }

    public function isAuthCodeRevoked($codeId): bool
    {
        $code = $this->findOneBy([
            'identifier' => $codeId,
        ]);
        if ($code === null) {
            return false;
        }

        return $code->isRevoked();
    }
}
