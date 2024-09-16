<?php

namespace VaultPhp;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions as GuzzleRequestOptions;
use GuzzleHttp\Psr7\Request;

class Client
{
    const ENDPOINT        = 'endpoint';
    const TOKEN           = 'token';
    const VERSION         = 'version';
    const CONNECT_TIMEOUT = 'connect_timeout';
    const READ_TIMEOUT    = 'read_timeout';

    /** @var string */
    protected $endpoint;

    /** @var GuzzleClient */
    protected $guzzleClient;

    /** @var string */
    protected $apiVersion;

    /** @var string */
    protected $token;

    /** @var float */
    protected $connectTimeout;

    /** @var float */
    protected $readTimeout;

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
        $this->endpoint       = isset($config[self::ENDPOINT]) ? $config[self::ENDPOINT] : 'https://localhost:8200';
        $this->token          = isset($config[self::TOKEN]) ? $config[self::TOKEN] : null;
        $this->apiVersion     = isset($config[self::VERSION]) ? $config[self::VERSION] : '1';
        $this->connectTimeout = isset($config[self::CONNECT_TIMEOUT]) ? floatval($config[self::CONNECT_TIMEOUT]) : 0.0;
        $this->readTimeout    = isset($config[self::READ_TIMEOUT]) ? floatval($config[self::READ_TIMEOUT]) : 0.0;
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
     * @param array|null $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request($method, $uriPath, array $options = [])
    {
        $request = new Request(
            $method,
            $this->makeUri($uriPath),
            ['X-Vault-Token' => $this->token]
        );

        $options[GuzzleRequestOptions::CONNECT_TIMEOUT] = $this->connectTimeout;
        $options[GuzzleRequestOptions::TIMEOUT]         = $this->readTimeout;

        return $this->guzzleClient->send($request, $options);
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
