<?php

namespace App;

use Bref\SymfonyBridge\BrefKernel;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;

class Kernel extends BrefKernel
{
    use MicroKernelTrait;

    public function getBuildDir(): string
    {
        if ($this->environment !== 'prod') {
            return parent::getCacheDir();
        }

        /** @noinspection ProjectDirParameter */
        return $this->getProjectDir() . '/var/cache/' . $this->environment;
    }
}
