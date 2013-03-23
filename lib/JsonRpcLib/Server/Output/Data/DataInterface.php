<?php

namespace JsonRpcLib\Server\Output\Data;

interface DataInterface
{
    /**
     *
     * @param string $data
     */
    public function write($data);
}
