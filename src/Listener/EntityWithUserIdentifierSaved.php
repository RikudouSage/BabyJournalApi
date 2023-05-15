<?php

namespace App\Listener;

use App\EntityType\OAuthEntityWithUserIdentifier;
use App\Repository\UserRepository;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Uid\Uuid;

final readonly class EntityWithUserIdentifierSaved implements EventSubscriber
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [Events::prePersist, Events::preUpdate];
    }

    public function prePersist(PrePersistEventArgs|PreUpdateEventArgs $event): void
    {
        $entity = $event->getObject();
        if (!$entity instanceof OAuthEntityWithUserIdentifier) {
            return;
        }
        $entity->setUser($this->userRepository->find(Uuid::fromString($entity->getUserIdentifier())));
    }

    public function preUpdate(PreUpdateEventArgs $event): void
    {
        $this->prePersist($event);
    }
}
