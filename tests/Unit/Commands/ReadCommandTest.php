<?php

namespace VaultPhp\Test\Unit;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use VaultPhp\Client;
use VaultPhp\Commands\ReadCommand;
use VaultPhp\Responses\ReadResponse;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

class ReadCommandTest extends TestCase
{
    public function testRunSendsCorrectRequestAndReturnsCorrectResponse()
    {
        $expectedApiVersion = 0;
        $expectedEndpoint   = 'https://localhost';
        $expectedToken      = 'asds89j1239e';
        $expectedUriPath    = 'secret/test';
        $expectedMethod     = 'GET';
        $expectedParams     = [];

        $expectedGuzzleRequest = new Request(
            $expectedMethod,
            $expectedEndpoint . '/v' . $expectedApiVersion . '/' . ltrim($expectedUriPath, '/'),
            ['X-Vault-Token' => $expectedToken]
        );
        $expectedGuzzleResponse = new GuzzleResponse();

        $guzzleClient = $this->getMockBuilder(GuzzleClient::class)
            ->setMethods(['send'])
            ->getMock();
        $guzzleClient
            ->expects($this->once())
            ->method('send')
            ->with($expectedGuzzleRequest, $expectedParams)
            ->will($this->returnValue($expectedGuzzleResponse));

        $client = new Client($guzzleClient, [
            'version'  => $expectedApiVersion,
            'endpoint' => $expectedEndpoint,
            'token'    => $expectedToken,
        ]);

        $readCommand     = new ReadCommand($client, [$expectedUriPath]);
        $expectedResponse = new ReadResponse($readCommand, $expectedGuzzleResponse);

        $this->assertEquals($expectedResponse, $readCommand->run());
    }
}
