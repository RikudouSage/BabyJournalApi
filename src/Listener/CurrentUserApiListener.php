<?php

namespace App\Listener;

use App\Entity\User;
use LogicException;
use Rikudou\JsonApiBundle\ApiEvents;
use Rikudou\JsonApiBundle\Events\RouterPreroutingEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class CurrentUserApiListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly Security $security,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApiEvents::PREROUTING => 'onRequest',
        ];
    }

    public function onRequest(RouterPreroutingEvent $event): void
    {
        if (
            $event->getController()->getClass() !== User::class
            || $event->getId() !== 'me'
        ) {
            return;
        }

        $user = $this->security->getUser() ?? throw new LogicException('Unauthorized');
        assert($user instanceof User);
        $event->setId($user->getId());
    }
}
