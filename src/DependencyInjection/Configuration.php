<?php

namespace Anera\HealthCheck\DependencyInjection;

use Anera\HealthCheck\Response\ResponseBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpFoundation\Response;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('anera_health_check');

        $rootNode = method_exists($treeBuilder, 'getRootNode')
            ? $treeBuilder->getRootNode()
            : $treeBuilder->root('anera_health_check'); //older Symfony configuration method.

        $rootNode
            ->children()
                ->scalarNode('response_builder')->defaultValue(ResponseBuilder::class)->cannotBeEmpty()->end()
                ->scalarNode('response_http_status')->defaultValue(Response::HTTP_OK)->cannotBeEmpty()->end()
                ->scalarNode('default_response_format')->defaultValue('application/json')->cannotBeEmpty()->end()
                ->arrayNode('response_contents')->defaultValue($this->getDefaultResponseContents())
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('content_type')->cannotBeEmpty()->end()
                            ->scalarNode('content')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('response_additional_headers')->defaultValue([])
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->cannotBeEmpty()->end()
                            ->scalarNode('value')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

    protected function getDefaultResponseContents(): array
    {
        return [
            [
                'content_type' => 'application/json',
                'content' => '{"health_check_status":"ok"}'
            ],
            [
                'content_type' => 'application/xml',
                'content' => '<?xml version="1.0" encoding="UTF-8"?><status>ok</status>'
            ],
            [
                'content_type' => 'text/html',
                'content' => '<!DOCTYPE html><head><title>Health check status</title></head><body><p class="health-check">Health check status: <b>ok</b></p></body></html>'
            ]
        ];
    }
}
