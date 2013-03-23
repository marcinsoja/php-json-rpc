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
        echo $data;
    }
}
