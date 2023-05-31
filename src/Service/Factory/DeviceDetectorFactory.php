<?php

namespace App\Service\Factory;

use DeviceDetector\ClientHints;
use DeviceDetector\DeviceDetector;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class DeviceDetectorFactory
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    public function create(): DeviceDetector
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            $detector =  new DeviceDetector();
        } else {
            $detector = new DeviceDetector(
                $request->headers->get('User-Agent'),
                ClientHints::factory(array_map(
                    fn (array $value) => $value[array_key_first($value)],
                    $request->headers->all()),
                ),
            );
        }

        $detector->parse();
        return $detector;
    }
}
