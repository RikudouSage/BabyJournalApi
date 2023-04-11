<?php

namespace App\Listener;

use App\Entity\Child;
use App\EntityType\ActivityRepository;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final readonly class ChildRemoveListener implements EventSubscriber
{
    /**
     * @param iterable<ActivityRepository> $activityRepositories
     */
    public function __construct(
        #[TaggedIterator('app.activity.repository')]
        private iterable $activityRepositories,
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [Events::preRemove];
    }

    public function preRemove(PreRemoveEventArgs $event): void
    {
        if (!$event->getObject() instanceof Child) {
            return;
        }

        foreach ($this->activityRepositories as $repository) {
            $entities = $repository->findBy([
                'child' => $event->getObject(),
            ]);
            foreach ($entities as $entity) {
                $event->getObjectManager()->remove($entity);
            }
        }
    }
}
