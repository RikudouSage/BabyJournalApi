<?php

namespace App\Controller;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Client\Browser;
use DeviceDetector\Parser\OperatingSystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class DeviceController extends AbstractController
{
    #[Route('/device/info', name: 'app.device.info')]
    public function getDeviceInfo(DeviceDetector $deviceDetector): JsonResponse
    {
        $osFamily = OperatingSystem::getOsFamily($deviceDetector->getOs('name'));
        $browserFamily = Browser::getBrowserFamily($deviceDetector->getClient('name'));

        return new JsonResponse([
            'phone' => $deviceDetector->isSmartphone() || $deviceDetector->isTablet() || $deviceDetector->isPhablet(),
            'ios' => $osFamily === 'iOS',
            'android' => $osFamily === 'Android',
            'chrome' => $browserFamily === 'Chrome',
            'safari' => $browserFamily === 'Safari',
        ]);
    }
}
