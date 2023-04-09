<?php

namespace App\Listener;

use App\Entity\User;
use App\EntityType\Activity;
use Doctrine\ORM\EntityManagerInterface;
use Rikudou\JsonApiBundle\ApiEntityEvents;
use Rikudou\JsonApiBundle\Events\EntityPreCreateEvent;
use Rikudou\JsonApiBundle\Exception\JsonApiErrorException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;

final readonly class ActivityCreatedListener implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
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
        if (!$entity instanceof Activity) {
            return;
        }

        if ($entity->getChild() === null) {
            $currentUser = $this->security->getUser();
            assert($currentUser instanceof User);
            $currentChild = $currentUser->getSelectedChild();
            if ($currentChild === null) {
                throw new JsonApiErrorException(
                    'No child is selected as active, you must specify child for this activity manually.',
                    Response::HTTP_BAD_REQUEST,
                );
            }
            $entity->setChild($currentChild);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }
    }
}
