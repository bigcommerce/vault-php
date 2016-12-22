<?php

namespace VaultPhp\Test\Unit;

use GuzzleHttp\Psr7\Request;
use VaultPhp\Client;
use VaultPhp\Commands\ReadCommand;
use VaultPhp\Responses\ReadResponse;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

class ReadCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testRunSendsCorrectRequestAndReturnsCorrectResponse()
    {
        $expectedApiVersion = 0;
        $expectedEndpoint   = 'https://localhost';
        $expectedToken      = 'asds89j1239e';
        $expectedUriPath    = 'secret/test';
        $expectedMethod     = 'GET';
        $expectedOptions    = [
            'timeout'         => 0.0,
            'connect_timeout' => 0.0,
        ];

        $expectedGuzzleRequest = new Request(
            $expectedMethod,
            $expectedEndpoint . '/v' . $expectedApiVersion . '/' . ltrim($expectedUriPath, '/'),
            ['X-Vault-Token' => $expectedToken]
        );
        $expectedGuzzleResponse = new GuzzleResponse();

        $guzzleClient = $this->getMock(GuzzleClient::class, ['send']);
        $guzzleClient
            ->expects($this->once())
            ->method('send')
            ->with($expectedGuzzleRequest, $expectedOptions)
            ->will($this->returnValue($expectedGuzzleResponse));

        $client = new Client($guzzleClient, [
            Client::VERSION  => $expectedApiVersion,
            Client::ENDPOINT => $expectedEndpoint,
            Client::TOKEN    => $expectedToken,
        ]);

        $readCommand     = new ReadCommand($client, [$expectedUriPath]);
        $expectedResponse = new ReadResponse($readCommand, $expectedGuzzleResponse);

        $this->assertEquals($expectedResponse, $readCommand->run());
    }
}
