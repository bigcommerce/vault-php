<?php

namespace VaultPhp\Responses;

use GuzzleHttp\Psr7\Response;

use VaultPhp\Client;

class ReadResponse extends AbstractResponse
{
    /**
     * @return mixed
     */
    public function getLeaseId()
    {
        return $this->jsonBody['lease_id'];
    }

    /**
     * @return mixed
     */
    public function getLeaseDuration()
    {
        return $this->jsonBody['leaseDuration'];
    }

    /**
     * @return mixed
     */
    public function getRenewable()
    {
        return $this->jsonBody['renewable'];
    }

    /**
     * @param null $key
     * @param null $default
     * @return null
     */
    public function getData($key = null, $default = null)
    {
        $data = $this->jsonBody['data'];

        if (!$key) {
            return $data;
        }

        return isset($data[$key]) ? $data[$key] : $default;
    }
}
