<?php

namespace JsonRpcLib\Server\Output;

class Response
{
    /**
     * A String specifying the version of the JSON-RPC protocol. MUST be exactly "2.0".
     *
     * @var string
     */
    public $jsonrpc = '2.0';

    /**
     * This member is REQUIRED on success.
     * This member MUST NOT exist if there was an error invoking the method.
     * The value of this member is determined by the method invoked on the Server.
     *
     * @var mixed
     */
    public $result = null;

    /**
     * This member is REQUIRED on error.
     * This member MUST NOT exist if there was no error triggered during invocation.
     * The value for this member MUST be an Object as defined in section 5.1.
     *
     * @var array
     */
    public $error = null;

    /**
     * This member is REQUIRED.
     * It MUST be the same as the value of the id member in the Request Object.
     * If there was an error in detecting the id in the Request object
     * (e.g. Parse error/Invalid Request), it MUST be Null.
     *
     * @var mixed
     */
    public $id = null;

    /**
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        foreach (array('result', 'error', 'id') as $name) {
            if (array_key_exists($name, $data)) {
                $this->$name = $data[$name];
            }
        }
    }

    /**
     *
     * @return array
     */
    public function toArray()
    {
        $array = (array) $this;

        if ($array['error'] instanceof Error) {

            unset($array['result']);

            $errors = array(
                Error::PARSE_ERROR,
                Error::INVALID_REQUEST
            );

            if (in_array($array['error']->getCode(), $errors)) {
                $array['id'] = null;
            }

             $array['error'] = $array['error']->toArray();

        } else {

            if (null === $array['id']) {
                return array();
            }

            unset($array['error']);
        }

        return $array;
    }
}
