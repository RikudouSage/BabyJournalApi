<?php

namespace App\Listener;

use App\Entity\User;
use App\EntityType\HasParentalUnit;
use Rikudou\JsonApiBundle\ApiEntityEvents;
use Rikudou\JsonApiBundle\Events\EntityPreCreateEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class ParentedEntitiesPreCreateListener implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApiEntityEvents::PRE_CREATE => 'preCreate',
        ];
    }

    public function preCreate(EntityPreCreateEvent $event): void
    {
        $entity = $event->getEntity();
        if (!$entity instanceof HasParentalUnit) {
            return;
        }

        $currentUser = $this->security->getUser();
        assert($currentUser instanceof User);
        $parentalUnit = $currentUser->getParentalUnit();

        $entity->setParentalUnit($parentalUnit);
    }
}
