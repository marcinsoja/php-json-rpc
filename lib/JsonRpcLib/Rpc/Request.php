<?php

namespace JsonRpcLib\Rpc;

use \JsonRpcLib\Server\Exception;
use \JsonRpcLib\Server\Output\Error;

class Request
{
    /**
     * A String specifying the version of the JSON-RPC protocol. MUST be exactly "2.0".
     *
     * @var string
     */
    protected $jsonrpc = null;

    /**
     * A String containing the name of the method to be invoked.
     * Method names that begin with the word rpc followed by a period
     * character (U+002E or ASCII 46) are reserved for rpc-internal methods
     * and extensions and MUST NOT be used for anything else.
     *
     * @var string
     */
    protected $method = null;

    /**
     * A Structured value that holds the parameter values to be used during
     * the invocation of the method. This member MAY be omitted.
     *
     * @var array
     */
    protected $params = null;

    /**
     * An identifier established by the Client that MUST contain a String,
     * Number, or NULL value if included. If it is not included it is assumed
     * to be a notification. The value SHOULD normally not be Null and
     * Numbers SHOULD NOT contain fractional parts
     *
     * @var mixed
     */
    protected $id = null;

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
     * @return string
     */
    public function getJsonrpc()
    {
        return $this->jsonrpc;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @param  string    $content
     * @throws Exception
     */
    public function valid()
    {
        if ('2.0' != $this->jsonrpc) {
            throw new Exception(Error::INVALID_REQUEST(), Error::INVALID_REQUEST);
        }

        if (empty($this->method)) {
            throw new Exception(Error::INVALID_REQUEST(), Error::INVALID_REQUEST);
        }

        if (!preg_match('/^[a-z][a-z0-9_.]*$/i', $this->method)) {
            throw new Exception(Error::INVALID_REQUEST(), Error::INVALID_REQUEST);
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
