<?php

namespace VaultPhp\Test\Unit;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use VaultPhp\Client;
use GuzzleHttp\Client as GuzzleClient;

class ClientTest extends TestCase
{
    public function testSetConfig()
    {
        $expectedApiVersion = 0;
        $expectedEndpoint   = 'https://localhost';
        $expectedToken      = 'asdf';

        $client = new Client(new GuzzleClient, []);
        $client->setConfig([
            'version'  => $expectedApiVersion,
            'endpoint' => $expectedEndpoint,
            'token'    => $expectedToken,
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
            'version'  => $expectedApiVersion,
            'endpoint' => $expectedEndpoint,
            'token'    => $expectedToken,
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
        $expectedParams     = [''];

        $expectedRequest = new Request(
            $expectedMethod,
            $expectedEndpoint . '/v' . $expectedApiVersion . '/' . ltrim($expectedUriPath, '/'),
            ['X-Vault-Token' => $expectedToken]
        );

        $guzzleClient = $this->getMockBuilder(GuzzleClient::class)
            ->onlyMethods(['send'])
            ->getMock();
        $guzzleClient
            ->expects($this->once())
            ->method('send')
            ->with($expectedRequest, $expectedParams);

        $client = new Client($guzzleClient, [
            'version'  => $expectedApiVersion,
            'endpoint' => $expectedEndpoint,
            'token'    => $expectedToken,
        ]);
        $client->request($expectedMethod, $expectedUriPath, $expectedParams);
    }
}
