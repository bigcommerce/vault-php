<?php

namespace VaultPhp\Test\Unit;

use GuzzleHttp\Psr7\Request;
use VaultPhp\Client;
use GuzzleHttp\Client as GuzzleClient;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testSetConfig()
    {
        $expectedApiVersion = 0;
        $expectedEndpoint   = 'https://localhost';
        $expectedToken      = 'asdf';

        $client = new Client(new GuzzleClient, []);
        $client->setConfig([
            Client::VERSION  => $expectedApiVersion,
            Client::ENDPOINT => $expectedEndpoint,
            Client::TOKEN    => $expectedToken,
        ]);

        $this->assertEquals($expectedApiVersion, $client->getApiVersion());
        $this->assertEquals($expectedEndpoint, $client->getEndpoint());
        $this->assertEquals($expectedToken, $client->getToken());
    }

    public function testConstructorSetsConfig()
    {
        $expectedApiVersion = 0;
        $expectedEndpoint   = 'https://localhost';
        $expectedToken      = 'asdf';

        $client = new Client(new GuzzleClient, [
            Client::VERSION  => $expectedApiVersion,
            Client::ENDPOINT => $expectedEndpoint,
            Client::TOKEN    => $expectedToken,
        ]);

        $this->assertEquals($expectedApiVersion, $client->getApiVersion());
        $this->assertEquals($expectedEndpoint, $client->getEndpoint());
        $this->assertEquals($expectedToken, $client->getToken());
    }

    public function testRequestSendsCorrectRequest()
    {
        $expectedApiVersion = 0;
        $expectedEndpoint   = 'https://localhost';
        $expectedToken      = 'asds89j1239e';
        $expectedUriPath    = 'secret/test';
        $expectedMethod     = 'POST';
        $expectedParams     = [
            'timeout'         => 30.0,
            'connect_timeout' => 2.0,
        ];

        $expectedRequest = new Request(
            $expectedMethod,
            $expectedEndpoint . '/v' . $expectedApiVersion . '/' . ltrim($expectedUriPath, '/'),
            ['X-Vault-Token' => $expectedToken]
        );

        $guzzleClient = $this->getMock(GuzzleClient::class, ['send']);
        $guzzleClient
            ->expects($this->once())
            ->method('send')
            ->with($expectedRequest, $expectedParams);

        $client = new Client($guzzleClient, [
            Client::VERSION         => $expectedApiVersion,
            Client::ENDPOINT        => $expectedEndpoint,
            Client::TOKEN           => $expectedToken,
            Client::READ_TIMEOUT    => $expectedParams['timeout'],
            Client::CONNECT_TIMEOUT => $expectedParams['connect_timeout'],
        ]);
        $client->request($expectedMethod, $expectedUriPath);
    }
}
