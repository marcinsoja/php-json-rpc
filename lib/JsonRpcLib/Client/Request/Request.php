<?php

namespace JsonRpcLib\Client\Request;

use \JsonRpcLib\Client\Response\Response;

class Request extends \JsonRpcLib\Rpc\Request
{
    /**
     *
     * @var \JsonRpcLib\Client\Response\Response
     */
    private $response = null;

    /**
     *
     * @param \JsonRpcLib\Client\Response\Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return \JsonRpcLib\Client\Response\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function toArray()
    {
        return array(
            'jsonrpc' => $this->getJsonrpc(),
            'method' => $this->getMethod(),
            'params' => $this->getParams(),
            'id' => $this->getId()
        );
    }
}
