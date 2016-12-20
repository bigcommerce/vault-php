<?php

namespace VaultPhp\Commands;

use VaultPhp\Responses\DeleteResponse;

class DeleteCommand extends AbstractCommand
{
    /** @var string */
    protected $method = 'DELETE';

    protected $responseClass = DeleteResponse::class;

    /**
     * @param array $parameters
     */
    protected function setRequestParameters(array $parameters)
    {
        list($path) = $parameters;

        $this->uriPath = '/'.ltrim($path, '/');
    }
}
