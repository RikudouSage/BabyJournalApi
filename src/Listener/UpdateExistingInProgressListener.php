<?php

namespace App\Listener;

use App\Entity\SharedInProgressActivity;
use App\Repository\SharedInProgressActivityRepository;
use JsonException;
use Rikudou\JsonApiBundle\ApiEntityEvents;
use Rikudou\JsonApiBundle\ApiEvents;
use Rikudou\JsonApiBundle\Events\EntityPreParseEvent;
use Rikudou\JsonApiBundle\Events\RouterPreroutingEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class UpdateExistingInProgressListener implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private SharedInProgressActivityRepository $repository,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApiEvents::PREROUTING => 'prerouting',
        ];
    }

    public function prerouting(RouterPreroutingEvent $event): void
    {
        if ($event->getController()->getClass() !== SharedInProgressActivity::class) {
            return;
        }
        if ($event->getId() !== null) {
            return;
        }
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            return;
        }

        try {
            $content = json_decode($request->getContent(), true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return;
        }

        $activityType = $content['data']['attributes']['activityType'] ?? null;
        if ($activityType === null) {
            return;
        }

        $entity = $this->repository->findOneBy([
            'activityType' => $activityType,
        ]);
        if ($entity === null) {
            return;
        }

        $event->setId($entity->getId());
        $content['data']['id'] = (string) $entity->getId();

        $request->setMethod(Request::METHOD_PATCH);
        $request->initialize(
            $request->query->all(),
            $request->request->all(),
            $request->attributes->all(),
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all(),
            json_encode($content),
        );
    }
}
