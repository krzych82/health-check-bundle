<?php

namespace Anera\HealthCheck\Response;

use Symfony\Component\HttpFoundation\Response;

class ResponseBuilder implements ResponseBuilderInterface, ResponseBuilderConfigurableInterface
{
    protected array $additionalHeaders;

    protected array $responseContents;

    protected string $defaultFormat;

    protected int $httpStatus;

    /**
     * @param string $contentType
     * @return Response
     */
    public function getResponse(string $contentType): Response
    {
        $content = $this->responseContents[$contentType] ?? $this->responseContents[$this->defaultFormat];

        $contentTypeHeader = [
            'Content-Type' => isset($this->responseContents[$contentType]) ? $contentType : $this->defaultFormat
        ];

        return new Response(
            $content,
            $this->httpStatus,
            array_merge($contentTypeHeader, $this->additionalHeaders)
        );
    }

    /**
     * @param array $additionalHeaders
     */
    public function setAdditionalHeaders(array $additionalHeaders): void
    {
        $this->additionalHeaders = $additionalHeaders;
    }

    /**
     * @param array $responseContents
     */
    public function setResponseContents(array $responseContents): void
    {
        $this->responseContents = $responseContents;
    }

    /**
     * @param string $defaultFormat
     */
    public function setDefaultFormat(string $defaultFormat): void
    {
        $this->defaultFormat = $defaultFormat;
    }

    /**
     * @param int $httpStatus
     */
    public function setResponseHttpStatus(int $httpStatus): void
    {
        $this->httpStatus = $httpStatus;
    }
}