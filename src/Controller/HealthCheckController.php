<?php

namespace Anera\HealthCheck\Controller;

use Anera\HealthCheck\Response\ResponseBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HealthCheckController
{
    public function status(Request $request, ResponseBuilderInterface $responseBuilder): Response
    {
        return $responseBuilder->getResponse($request->headers->get('Content-Type', 'application/json'));
    }
}