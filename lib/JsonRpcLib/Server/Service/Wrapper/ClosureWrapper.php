<?php

namespace JsonRpcLib\Server\Service\Wrapper;

use \JsonRpcLib\Server\Output\Error;
use \JsonRpcLib\Server\Exception;

class ClosureWrapper implements WrapperInterface, CallableInterface
{
    /**
     *
     * @var \Closure
     */
    private $closure = null;

    /**
     *
     * @param \Closure $closure
     */
    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * @param  string                       $name
     * @param  array                        $params
     * @return mixed
     * @throws \JsonRpcLib\Server\Exception
     */
    public function execute($name, array $params)
    {
        $reflectionFunction = new \ReflectionFunction($this->closure);

        $helper = new Helper();

        if (false == $helper->isValidParameters($reflectionFunction, $params)) {
            throw new Exception(Error::INVALID_PARAMS(), Error::INVALID_PARAMS
            );
        }

        $parameters = $helper->normalizeParameters(
            $helper->getParametersNames($reflectionFunction),
            $params
        );

        if (false == $helper->isValidParameters($reflectionFunction, $parameters)) {
            throw new Exception(Error::INVALID_PARAMS(), Error::INVALID_PARAMS);
        }

        return call_user_func_array($this->closure, $parameters);
    }
}
