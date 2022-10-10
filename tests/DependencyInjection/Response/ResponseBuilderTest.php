<?php

namespace Anera\HealthCheck\Tests\DependencyInjection\Response;

use Anera\HealthCheck\Response\ResponseBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ResponseBuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider contentTypeProvider
     */
    public function testGetResponseContents(array $input, array $output)
    {
        $responseBuilder = new ResponseBuilder();
        $responseBuilder->setResponseHttpStatus(Response::HTTP_OK);
        $responseBuilder->setDefaultFormat('application/json');
        $responseBuilder->setResponseContents(
            [
                'application/json' => '{"health_check_status":"ok"}',
                'application/xml' => '<?xml version="1.0" encoding="UTF-8"?><status>ok</status>',
                'text/html' => '<!DOCTYPE html><head><title>Health check status</title></head><body><p class="health-check">Health check status: <b>ok</b></p></body></html>'
            ]
        );
        $responseBuilder->setAdditionalHeaders([]);

        $response = $responseBuilder->getResponse($input['Content-Type']);

        $this->assertEquals($output['Content'], $response->getContent());
        $this->assertEquals($output['Content-Type'], $response->headers->get('Content-Type'));
    }

    /**
     * @test
     */
    public function testAdditionalHeader()
    {
        $responseBuilder = new ResponseBuilder();
        $responseBuilder->setResponseHttpStatus(Response::HTTP_OK);
        $responseBuilder->setDefaultFormat('application/json');
        $responseBuilder->setResponseContents(
            [
                'application/json' => '{"health_check_status":"ok"}',
            ]
        );
        $responseBuilder->setAdditionalHeaders(
            [
                'some' => 'additional',
            ]
        );

        $response = $responseBuilder->getResponse('application/json');

        $this->assertEquals('additional', $response->headers->get('some'));
    }

    /**
     * @test
     * @dataProvider httpStatusProvider
     */
    public function testHttpStatus(int $status)
    {
        $responseBuilder = new ResponseBuilder();
        $responseBuilder->setResponseHttpStatus($status);
        $responseBuilder->setDefaultFormat('application/json');
        $responseBuilder->setResponseContents(
            [
                'application/json' => '{"health_check_status":"ok"}',
            ]
        );
        $responseBuilder->setAdditionalHeaders([]);

        $response = $responseBuilder->getResponse('application/json');

        $this->assertEquals($status, $response->getStatusCode());
    }

    public function contentTypeProvider(): array
    {
        return [
            [
                'input' => [
                    'Content-Type' => 'application/xml'
                ],
                'output' => [
                    'Content-Type' => 'application/xml',
                    'Content' => '<?xml version="1.0" encoding="UTF-8"?><status>ok</status>'
                ]
            ],
            [
                'input' => [
                    'Content-Type' => 'text/html'
                ],
                'output' => [
                    'Content-Type' => 'text/html',
                    'Content' => '<!DOCTYPE html><head><title>Health check status</title></head><body><p class="health-check">Health check status: <b>ok</b></p></body></html>'
                ]
            ],
            [
                'input' => [
                    'Content-Type' => 'application/json'
                ],
                'output' => [
                    'Content-Type' => 'application/json',
                    'Content' => '{"health_check_status":"ok"}'
                ]
            ],
            [
                'input' => [
                    'Content-Type' => 'some/unknown'
                ],
                'output' => [
                    'Content-Type' => 'application/json',
                    'Content' => '{"health_check_status":"ok"}'
                ]
            ]
        ];
    }

    public function httpStatusProvider(): array
    {
        return [
            [
                Response::HTTP_OK
            ],
            [
                Response::HTTP_ACCEPTED
            ],
            [
                Response::HTTP_I_AM_A_TEAPOT
            ]
        ];
    }
}