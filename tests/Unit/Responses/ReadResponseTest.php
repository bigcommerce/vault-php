<?php

namespace VaultPhp\Test\Unit;

use PHPUnit\Framework\TestCase;
use VaultPhp\Client;
use VaultPhp\Commands\ReadCommand;
use VaultPhp\Responses\ReadResponse;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

class ReadResponseTest extends TestCase
{
    public function testGetStatus()
    {
        $readResponse = new ReadResponse(
            new ReadCommand(new Client(new GuzzleClient(), []), ['secret/test']),
            new GuzzleResponse(204)
        );
        $this->assertEquals(204, $readResponse->getStatus());
    }

    public function testGetCommand()
    {
        $expectedCommand = new ReadCommand(new Client(new GuzzleClient(), []), ['secret/test']);
        $readResponse    = new ReadResponse($expectedCommand, new GuzzleResponse());

        $this->assertEquals($expectedCommand, $readResponse->getCommand());
    }

    public function testGetWarnings()
    {
        $expectedPayload = ['warnings' => 'asdf'];
        $readResponse    = new ReadResponse(
            new ReadCommand(new Client(new GuzzleClient(), []), ['secret/test']),
            new GuzzleResponse(204, [], json_encode($expectedPayload, true))
        );

        $this->assertEquals($expectedPayload['warnings'], $readResponse->getWarnings());
    }

    public function testGetLeaseId()
    {
        $expectedPayload = ['lease_id' => 123];
        $readResponse    = new ReadResponse(
            new ReadCommand(new Client(new GuzzleClient(), []), ['secret/test']),
            new GuzzleResponse(204, [], json_encode($expectedPayload, true))
        );

        $this->assertEquals($expectedPayload['lease_id'], $readResponse->getLeaseId());
    }

    public function testGetLeaseDuration()
    {
        $expectedPayload = ['leaseDuration' => 3600];
        $readResponse    = new ReadResponse(
            new ReadCommand(new Client(new GuzzleClient(), []), ['secret/test']),
            new GuzzleResponse(204, [], json_encode($expectedPayload, true))
        );

        $this->assertEquals($expectedPayload['leaseDuration'], $readResponse->getLeaseDuration());
    }

    public function testGetRenewable()
    {
        $expectedPayload = ['renewable' => true];
        $readResponse    = new ReadResponse(
            new ReadCommand(new Client(new GuzzleClient(), []), ['secret/test']),
            new GuzzleResponse(204, [], json_encode($expectedPayload, true))
        );

        $this->assertEquals($expectedPayload['renewable'], $readResponse->getRenewable());
    }

    public function testGetData()
    {
        $expectedPayload = ['data' => [
            'cert'   => 'dsafff24f443fd',
            'expiry' => 1482210900,
        ]];
        $readResponse    = new ReadResponse(
            new ReadCommand(new Client(new GuzzleClient(), []), ['secret/test']),
            new GuzzleResponse(204, [], json_encode($expectedPayload, true))
        );

        $this->assertEquals($expectedPayload['data'], $readResponse->getData());
    }

    public function testGetDataReturnsCorrectValueForKey()
    {
        $expectedPayload = ['data' => [
            'cert'   => 'dsafff24f443fd',
            'expiry' => 1482210900,
        ]];
        $readResponse    = new ReadResponse(
            new ReadCommand(new Client(new GuzzleClient(), []), ['secret/test']),
            new GuzzleResponse(204, [], json_encode($expectedPayload, true))
        );

        $this->assertEquals($expectedPayload['data']['cert'], $readResponse->getData('cert'));
    }

    public function testGetDataReturnsCorrectDefaultValueWhenKeyDoesntExist()
    {
        $expectedPayload = ['data' => [
            'cert'   => 'dsafff24f443fd',
            'expiry' => 1482210900,
        ]];
        $readResponse    = new ReadResponse(
            new ReadCommand(new Client(new GuzzleClient(), []), ['secret/test']),
            new GuzzleResponse(204, [], json_encode($expectedPayload, true))
        );

        $defaultValueForProvider = 'N/A';
        $this->assertEquals($defaultValueForProvider, $readResponse->getData('provider', $defaultValueForProvider));
    }
}
