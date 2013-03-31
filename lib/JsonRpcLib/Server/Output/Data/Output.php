<?php

namespace JsonRpcLib\Server\Output\Data;

class Output implements DataInterface
{
    /**
     *
     * @param string $data
     */
    public function write($data)
    {
        $this->sendHeaders($data);

        echo $data;
    }

    public function sendHeaders($data)
    {
        if (headers_sent()) {
            return;
        }

        if (empty($data)) {
            header('HTTP/1.1 204 No Content');

            return;
        }

        header('Content-Type: application/json');
    }
}
