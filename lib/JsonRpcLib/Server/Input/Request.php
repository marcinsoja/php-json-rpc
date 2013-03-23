<?php

namespace JsonRpcLib\Server\Input;

class Request
{
    /**
     * A String specifying the version of the JSON-RPC protocol. MUST be exactly "2.0".
     *
     * @var string
     */
    public $jsonrpc = null;

    /**
     * A String containing the name of the method to be invoked.
     * Method names that begin with the word rpc followed by a period
     * character (U+002E or ASCII 46) are reserved for rpc-internal methods
     * and extensions and MUST NOT be used for anything else.
     *
     * @var string
     */
    public $method = null;

    /**
     * A Structured value that holds the parameter values to be used during
     * the invocation of the method. This member MAY be omitted.
     *
     * @var array
     */
    public $params = null;

    /**
     * An identifier established by the Client that MUST contain a String,
     * Number, or NULL value if included. If it is not included it is assumed
     * to be a notification. The value SHOULD normally not be Null and
     * Numbers SHOULD NOT contain fractional parts
     *
     * @var mixed
     */
    public $id = null;

    /**
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        foreach (array('jsonrpc', 'method', 'params', 'id') as $name) {
            if (array_key_exists($name, $data)) {
                $this->$name = $data[$name];
            }
        }
    }

    /**
     *
     * @param  string    $content
     * @throws Exception
     */
    public function valid()
    {
        if ('2.0' != $this->jsonrpc) {
            throw new \JsonRpcLib\Server\Exception(
                \JsonRpcLib\Server\Output\Error::INVALID_REQUEST(),
                \JsonRpcLib\Server\Output\Error::INVALID_REQUEST
            );
        }

        if (empty($this->method)) {
            throw new \JsonRpcLib\Server\Exception(
                \JsonRpcLib\Server\Output\Error::INVALID_REQUEST(),
                \JsonRpcLib\Server\Output\Error::INVALID_REQUEST
            );
        }

        if (!preg_match('/^[a-z][a-z0-9_.]*$/i', $this->method)) {
            throw new \JsonRpcLib\Server\Exception(
                \JsonRpcLib\Server\Output\Error::INVALID_REQUEST(),
                \JsonRpcLib\Server\Output\Error::INVALID_REQUEST
            );
        }
    }

    /**
     *
     * @return boolean
     */
    public function isNotification()
    {
        return (null === $this->id);
    }

    /**
     * @return array
     */
    public function getParams()
    {
        $params = $this->params;

        if (null === $params) {
            $params = array();
        }

        if (is_object($params)) {
            $params = (array) $params;
        }

        if (false == is_array($params)) {
            $params = array($params);
        }

        return $params;
    }
}
