<?php

namespace JsonRpcLib\Server\Input\Data;

class Get implements DataInterface
{
    private $param = null;

    /**
     * @param string $param
     */
    public function __construct($param = 'data')
    {
        $this->param = $param;
    }
    /**
     * @return string
     */
    public function read()
    {
        return isset($_GET[$this->param])
            ? $_GET[$this->param]
            : ''
        ;
    }
}
