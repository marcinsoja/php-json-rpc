<?php

namespace JsonRpcLib\Server\Output;

use \JsonRpcLib\Rpc\Error;

class Response extends \JsonRpcLib\Rpc\Response
{
    /**
     *
     * @return array
     */
    public function toArray()
    {
        $array = array(
            'jsonrpc' => $this->getJsonrpc(),
            'result' => $this->getResult(),
            'error' => $this->getError(),
            'id' => $this->getId(),
        );

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
