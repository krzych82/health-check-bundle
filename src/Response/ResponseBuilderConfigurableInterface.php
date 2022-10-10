<?php

namespace Anera\HealthCheck\Response;

interface ResponseBuilderConfigurableInterface
{
    /**
     * @param array $additionalHeaders
     */
    public function setAdditionalHeaders(array $additionalHeaders);

    /**
     * @param array $responseContents
     */
    public function setResponseContents(array $responseContents);

    /**
     * @param string $defaultFormat
     */
    public function setDefaultFormat(string $defaultFormat);

    /**
     * @param int $httpStatus
     */
    public function setResponseHttpStatus(int $httpStatus);
}