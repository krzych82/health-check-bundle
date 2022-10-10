<?php

namespace Anera\HealthCheck\Response;

use Symfony\Component\HttpFoundation\Response;

interface ResponseBuilderInterface
{
    /**
     * @param string $contentType
     * @return Response
     */
    public function getResponse(string $contentType): Response;
}