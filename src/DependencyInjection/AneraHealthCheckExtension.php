<?php

namespace Anera\HealthCheck\DependencyInjection;

use Anera\HealthCheck\Response\ResponseBuilderConfigurableInterface;
use Anera\HealthCheck\Response\ResponseBuilderInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class AneraHealthCheckExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $responseBuilder = new Definition($config['response_builder']);
        $responseBuilder->setAutowired(true);

        if (is_subclass_of($responseBuilder->getClass(), ResponseBuilderConfigurableInterface::class)) {

            $additionalHeaders = [];
            foreach ($config['response_additional_headers'] as $additionalHeader) {
                $additionalHeaders[$additionalHeader['name']] = $additionalHeader['value'];
            }

            $responseContents = [];
            foreach ($config['response_contents'] as $responseContent) {
                $responseContents[$responseContent['content_type']] = $responseContent['content'];
            }

            if (!isset($responseContents[$config['default_response_format']])) {
                throw new InvalidConfigurationException('Content of default response format must be set in response_contents.');
            }

            $responseBuilder->addMethodCall('setResponseContents', [$responseContents])
                ->addMethodCall('setAdditionalHeaders', [$additionalHeaders])
                ->addMethodCall('setResponseHttpStatus', [$config['response_http_status']])
                ->addMethodCall('setDefaultFormat', [$config['default_response_format']]);
        }

        $container->setDefinition(ResponseBuilderInterface::class, $responseBuilder);
    }
}
