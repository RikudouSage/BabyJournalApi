<?php

namespace App\Listener;

use App\Entity\OAuthAuthorizedUserScope;
use App\Entity\OAuthClient;
use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

final class DeletePrivateKeyWhenNotNeededListener implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist, Events::preUpdate, Events::preRemove];
    }

    public function prePersist(PrePersistEventArgs|PreUpdateEventArgs|PreRemoveEventArgs $event): void
    {
        $entity = $event->getObject();
        if ($entity instanceof User) {
            if (!$entity->hasApplicationsConnected()) {
                $entity->setEncryptionKey(null);
            }
        }
        if ($entity instanceof OAuthAuthorizedUserScope) {
            $user = $entity->getOwner();
            if (!$user?->hasApplicationsConnected()) {
                $user?->setEncryptionKey(null);
            }
        }
        if ($entity instanceof OAuthClient) {
            foreach ($entity->getUsers() as $user) {
                if (!$user->hasApplicationsConnected()) {
                    $user->setEncryptionKey(null);
                }
            }
        }
    }

    public function preUpdate(PreUpdateEventArgs $event): void
    {
        $this->prePersist($event);
    }

    public function preRemove(PreRemoveEventArgs $event): void
    {
        $this->prePersist($event);
    }
}
