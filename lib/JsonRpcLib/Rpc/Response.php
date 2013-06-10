<?php

namespace JsonRpcLib\Rpc;

class Response
{
    /**
     * A String specifying the version of the JSON-RPC protocol. MUST be exactly "2.0".
     *
     * @var string
     */
    protected $jsonrpc = '2.0';

    /**
     * This member is REQUIRED on success.
     * This member MUST NOT exist if there was an error invoking the method.
     * The value of this member is determined by the method invoked on the Server.
     *
     * @var mixed
     */
    protected $result = null;

    /**
     * This member is REQUIRED on error.
     * This member MUST NOT exist if there was no error triggered during invocation.
     * The value for this member MUST be an Object as defined in section 5.1.
     *
     * @var array
     */
    protected $error = null;

    /**
     * This member is REQUIRED.
     * It MUST be the same as the value of the id member in the Request Object.
     * If there was an error in detecting the id in the Request object
     * (e.g. Parse error/Invalid Request), it MUST be Null.
     *
     * @var mixed
     */
    protected $id = null;

    /**
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        foreach (array('result', 'error', 'id', 'jsonrpc') as $name) {
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
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param array $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }
}
