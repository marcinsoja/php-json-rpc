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

        $isValidNumberOfParameters = $helper->isValidNumberOfParameters(
            $params,
            $reflectionFunction->getNumberOfRequiredParameters(),
            $reflectionFunction->getNumberOfParameters()
        );

        if (false == $isValidNumberOfParameters) {
            throw new Exception(Error::INVALID_PARAMS(), Error::INVALID_PARAMS);
        }

        $parametersNames = $helper->getParametersNames($reflectionFunction);

        $parameters = $helper->normalizeParameters(
            $parametersNames,
            $params
        );

         $isValid = $helper->isValidParameters(
            $parameters,
            $parametersNames,
            $reflectionFunction->getNumberOfRequiredParameters()
        );

        if (false == $isValid) {
            throw new Exception(Error::INVALID_PARAMS(), Error::INVALID_PARAMS);
        }

        return call_user_func_array($this->closure, $parameters);
    }
}
