<?php

namespace JsonRpcLib\Client\Request;

class Message
{
    private $requests = array();

    /**
     *
     * @param Request $request
     */
    public function addRequest(Request $request)
    {
        $this->requests[] = $request;
    }

    public function toJson()
    {
        $requests = array();
        foreach ($this->requests as $request) {
            $requests[] = $request->toArray();
        }

        $isBatch = (count($requests) > 1);

        if (false == $isBatch) {
            $requests = array_shift($requests);
        }

        return json_encode($requests);
    }

    public function send($adapter)
    {
        $responseString = $adapter->send($this->toJson());

        return $responseString;
    }
}
