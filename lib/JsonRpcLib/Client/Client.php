<?php

namespace JsonRpcLib\Client;

use \JsonRpcLib\Tools\Uuid;
use \JsonRpcLib\Client\Request\Request;
use \JsonRpcLib\Client\Response\Response;

class Client
{
    private $isBatch = false;

    private $requests = array();

    /**
     *
     * @var Adapter\AdapterInterface
     */
    private $adapter = null;

    public function __construct(Adapter\AdapterInterface $adapter = null)
    {
        $this->adapter = $adapter;
    }

    /**
     *
     * @param  string $method
     * @param  array  $arguments
     * @return mixed
     */
    public function call($method, $arguments = array())
    {
        $id = Uuid::v4();

        return $this->processRequest($method, $arguments, $id);
    }

    /**
     *
     * @param  string $method
     * @param  array  $arguments
     * @return mixed
     */
    public function notify($method, $arguments = array())
    {
        return $this->processRequest($method, $arguments);
    }

    public function begin()
    {
        $this->isBatch = true;
    }
    public function commit()
    {
        $this->isBatch = false;
        $this->sendRequests();
    }

    private function processRequest($method, $arguments, $id = null)
    {
        $params = array_filter(array(
            'method'    => $method,
            'params'    => $arguments,
            'id'        => $id,
        ));

        $response = new Response();

        $request = new Request($params);
        $request->setResponse($response);

        $this->requests[] = $request;

        if (false == $this->isBatch) {
            $this->sendRequests();
        }

        return $response;
    }

    private function sendRequests()
    {
        $message = new \JsonRpcLib\Client\Request\Message();
        foreach ($this->requests as $request) {
            $message->addRequest($request);
        }
        $message->send($this->adapter);
    }
}
