<?php

namespace VaultPhp\Test\Unit;

use GuzzleHttp\Psr7\Request;
use VaultPhp\Client;
use VaultPhp\Commands\DeleteCommand;
use VaultPhp\Responses\DeleteResponse;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

class DeleteCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testRunSendsCorrectRequestAndReturnsCorrectResponse()
    {
        $expectedApiVersion = 0;
        $expectedEndpoint   = 'https://localhost';
        $expectedToken      = 'asds89j1239e';
        $expectedUriPath    = 'secret/test';
        $expectedMethod     = 'DELETE';
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

        $deleteCommand     = new DeleteCommand($client, [$expectedUriPath]);
        $expectedResponse = new DeleteResponse($deleteCommand, $expectedGuzzleResponse);

        $this->assertEquals($expectedResponse, $deleteCommand->run());
    }
}
