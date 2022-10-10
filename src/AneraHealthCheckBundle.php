<?php

namespace Anera\HealthCheck;

use Anera\HealthCheck\DependencyInjection\AneraHealthCheckExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AneraHealthCheckBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        return new AneraHealthCheckExtension();
    }
}
