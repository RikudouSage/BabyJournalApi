<?php

namespace App\Repository;

use App\Entity\OAuthClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

/**
 * @extends ServiceEntityRepository<OAuthClient>
 *
 * @method OAuthClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method OAuthClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method OAuthClient[]    findAll()
 * @method OAuthClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class OAuthClientRepository extends ServiceEntityRepository implements ClientRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly PasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct($registry, OAuthClient::class);
    }

    public function save(OAuthClient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OAuthClient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getClientEntity($clientIdentifier): ?OAuthClient
    {
        return $this->findOneBy([
            'identifier' => $clientIdentifier,
        ]);
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        $entity = $this->getClientEntity($clientIdentifier);
        if ($entity === null) {
            return false;
        }

        if (!$entity->isConfidential() && $clientSecret === null) {
            return true;
        }

        if ($entity->getSecret() === null || $clientSecret === null) {
            return false;
        }

        return $this->passwordHasher->verify($entity->getSecret(), $clientSecret);
    }
}
