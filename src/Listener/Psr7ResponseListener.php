<?php

namespace App\Listener;

use Psr\Http\Message\ResponseInterface;
use Symfony\Bridge\PsrHttpMessage\HttpFoundationFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class Psr7ResponseListener implements EventSubscriberInterface
{
    public function __construct(
        private HttpFoundationFactoryInterface $httpFoundationFactory,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => 'onResponse',
        ];
    }

    public function onResponse(ViewEvent $event): void
    {
        $response = $event->getControllerResult();
        if (!$response instanceof ResponseInterface) {
            return;
        }

        $event->setResponse($this->httpFoundationFactory->createResponse($response));
    }
}
