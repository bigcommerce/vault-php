<?php

namespace VaultPhp;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;

class Client
{
    /** @var string */
    protected $endpoint;

    /** @var GuzzleClient */
    protected $guzzleClient;

    /** @var string */
    protected $apiVersion;

    /** @var string */
    protected $token;

    /**
     * @param GuzzleClient $guzzleClient
     * @param array $config
     */
    public function __construct(GuzzleClient $guzzleClient, array $config)
    {
        $this->guzzleClient = $guzzleClient;

        $this->setConfig($config);
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->apiVersion = isset($config['version']) ? $config['version'] : '1';
        $this->endpoint = isset($config['endpoint']) ? $config['endpoint'] : 'https://localhost:8200';
        $this->token = isset($config['token']) ? $config['token'] : null;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @return GuzzleClient
     */
    public function getGuzzleClient()
    {
        return $this->guzzleClient;
    }

    /**
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @param $uriPath
     * @return string
     */
    protected function makeUri($uriPath)
    {
        return $this->endpoint . '/v' . $this->apiVersion . '/' . ltrim($uriPath, '/');
    }

    /**
     * @param $method
     * @param $uriPath
     * @param array|null $params
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request($method, $uriPath, array $params = null)
    {
        $request = new Request(
            $method,
            $this->makeUri($uriPath),
            ['X-Vault-Token' => $this->token]
        );

        return $this->guzzleClient->send($request, $params);
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $class = 'VaultPhp\\Commands\\'.ucwords($method).'Command';
        $command = new $class($this, $parameters);
        return $command->run();
    }
}
