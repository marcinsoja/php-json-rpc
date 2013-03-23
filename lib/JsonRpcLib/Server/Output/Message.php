<?php

namespace JsonRpcLib\Server\Output;

class Message
{
    /**
     * @var Data\DataInterface
     */
    private $data = null;

    /**
     *
     * @var array
     */
    private $responses = array();

    /**
     *
     * @param \JsonRpcLib\Server\Output\Data\DataInterface $data
     */
    public function __construct(Data\DataInterface $data)
    {
        $this->data = $data;
    }

    /**
     *
     * @param \JsonRpcLib\Server\Output\Response $response
     */
    public function addResponse(Response $response)
    {
        $this->responses[] = $response;
    }

    /**
     *
     */
    public function write()
    {
        $isBatch = (count($this->responses) > 1);

        $responses = array();
        foreach ($this->responses as $response) {
            $responses[] = $response->toArray();
        }

        $responses = array_filter($responses);

        if (empty($responses)) {
            return;
        }

        if (false == $isBatch) {
            $responses = array_shift($responses);
        }

        $this->data->write(json_encode($responses));
    }
}
