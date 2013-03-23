<?php

namespace JsonRpcLib\Server\Input;

class Message implements \IteratorAggregate, \Countable
{
    /**
     * @var Data\DataInterface
     */
    private $data = null;

    private $requests = null;

    public function __construct(Data\DataInterface $data)
    {
        $this->data = $data;
    }

    private function buildRequests()
    {
        if (null === $this->requests) {

            $content = $this->data->read();

            $data = $this->decodeContent($content);

            if (!is_array($data)) {
                $data = array($data);
            }

            $requests = array();
            foreach ($data as $element) {
                $requests[] = new Request((array) $element);
            }

            $this->requests = $requests;
        }

        return $this->requests;
    }

    /**
     *
     * @param  string    $content
     * @throws Exception
     */
    private function decodeContent($content)
    {
        $data = json_decode($content);

        if (empty($data)) {
            throw new \JsonRpcLib\Server\Exception(
                \JsonRpcLib\Error\Code::PARSE_ERROR(),
                \JsonRpcLib\Error\Code::PARSE_ERROR
            );
        }

        return $data;
    }

    public function count()
    {
        $this->buildRequests();

        return count($this->requests);
    }

    public function getIterator()
    {
        $this->buildRequests();

        return new \ArrayIterator($this->requests);
    }
}
