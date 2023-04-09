<?php

namespace App\Listener;

use App\Entity\User;
use Rikudou\JsonApiBundle\ApiEntityEvents;
use Rikudou\JsonApiBundle\Events\EntityPreParseEvent;
use Rikudou\JsonApiBundle\Events\EntityPreUpdateEvent;
use Rikudou\JsonApiBundle\Exception\JsonApiErrorException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;

final readonly class UpdateUserApiListener implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApiEntityEvents::PRE_PARSE => 'preParse',
            ApiEntityEvents::PRE_UPDATE => 'preUpdate',
        ];
    }

    public function preParse(EntityPreParseEvent $event): void
    {
        $data = $event->getData();
        assert(isset($data['data']) && is_array($data['data']));

        if (!isset($data['data']['type']) || ($data['data']['type'] !== 'user' && $data['data']['type'] !== 'users')) {
            return;
        }

        unset($data['data']['relationships']['parentalUnit']);
        $event->setData($data);
    }

    public function preUpdate(EntityPreUpdateEvent $event): void
    {
        $entity = $event->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        $currentUser = $this->security->getUser();
        assert($currentUser instanceof User);

        if ((string) $entity->getId() !== (string) $currentUser->getId()) {
            throw new JsonApiErrorException('Cannot update different user', Response::HTTP_FORBIDDEN);
        }
    }
}
