<?php

namespace JsonRpcLib\Error;

class Code extends \JsonRpcLib\Util\Enum
{
    /**
     * Parse error - Invalid JSON was received by the server.
     * An error occurred on the server while parsing the JSON text.
     */
    const PARSE_ERROR = -32700;

    /**
     * Invalid Request - The JSON sent is not a valid Request object.
     */
    const INVALID_REQUEST = -32600;

    /**
     * Method not found - The method does not exist / is not available.
     */
    const METHOD_NOT_FOUND = -32601;

    /**
     * Invalid params - Invalid method parameter(s).
     */
    const INVALID_PARAMS = -32602;

    /**
     * Internal error - Internal JSON-RPC error.
     */
    const INTERNAL_ERROR = -32603;

    /**
     * Server error - Reserved for implementation-defined server-errors.
     *
     * -32000 to -32099
     */
    const SERVER_ERROR = -32000;

    /**
     * Default Enum value
     */
    const __default = -32700;
}
