<?php

namespace JsonRpcLib\Server;

class Exception extends \JsonRpcLib\Exception
{
    private $data = null;

    public function __construct($message = null, $code = 0, $previous = null, $data = null)
    {
        if (is_array($previous)) {
            $data = $previous;
            parent::__construct($message, $code);
        } else {
            parent::__construct($message, $code, $previous);
        }

        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
