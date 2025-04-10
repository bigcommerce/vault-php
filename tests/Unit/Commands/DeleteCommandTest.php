<?php

namespace VaultPhp\Test\Unit;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use VaultPhp\Client;
use VaultPhp\Commands\DeleteCommand;
use VaultPhp\Responses\DeleteResponse;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

class DeleteCommandTest extends TestCase
{
    public function testRunSendsCorrectRequestAndReturnsCorrectResponse()
    {
        $expectedApiVersion = 0;
        $expectedEndpoint   = 'https://localhost';
        $expectedToken      = 'asds89j1239e';
        $expectedUriPath    = 'secret/test';
        $expectedMethod     = 'DELETE';
        $expectedParams     = [];

        $expectedGuzzleRequest = new Request(
            $expectedMethod,
            $expectedEndpoint . '/v' . $expectedApiVersion . '/' . ltrim($expectedUriPath, '/'),
            ['X-Vault-Token' => $expectedToken]
        );
        $expectedGuzzleResponse = new GuzzleResponse();

        $guzzleClient = $this->getMockBuilder(GuzzleClient::class)
            ->onlyMethods(['send'])
            ->getMock();
        $guzzleClient
            ->expects($this->once())
            ->method('send')
            ->with($expectedGuzzleRequest, $expectedParams)
            ->willReturn($expectedGuzzleResponse);

        $client = new Client($guzzleClient, [
            'version'  => $expectedApiVersion,
            'endpoint' => $expectedEndpoint,
            'token'    => $expectedToken,
        ]);

        $deleteCommand     = new DeleteCommand($client, [$expectedUriPath]);
        $expectedResponse = new DeleteResponse($deleteCommand, $expectedGuzzleResponse);

        $this->assertEquals($expectedResponse, $deleteCommand->run());
    }
}
