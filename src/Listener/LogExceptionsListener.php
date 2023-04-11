<?php

namespace App\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class LogExceptionsListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    public function onException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $messages = [
            [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ],
        ];

        while ($exception = $exception->getPrevious()) {
            $messages[] = [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ];
        }

        error_log(json_encode($messages, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
