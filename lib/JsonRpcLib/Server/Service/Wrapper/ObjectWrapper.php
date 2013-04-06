<?php

namespace JsonRpcLib\Server\Service\Wrapper;

use \JsonRpcLib\Server\Output\Error;
use \JsonRpcLib\Server\Exception;

class ObjectWrapper implements WrapperInterface
{
    /**
     *
     * @var object
     */
    private $object = null;

    /**
     * @param object $object
     */
    public function __construct($object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException();
        }

        $this->object = $object;
    }

    /**
     *
     * @param  string                       $name
     * @param  array                        $params
     * @return mixed
     * @throws \JsonRpcLib\Server\Exception
     */
    public function execute($name, array $params)
    {
        if (false == method_exists($this->object, $name)) {
            throw new Exception(Error::METHOD_NOT_FOUND(), Error::METHOD_NOT_FOUND);
        }

        $reflectionMethod = new \ReflectionMethod(get_class($this->object), $name);

        $isAllowed = $reflectionMethod->isPublic()
            && false == $reflectionMethod->isStatic()
            && $reflectionMethod->isUserDefined()
        ;

        if (false == $isAllowed) {
            throw new Exception(Error::METHOD_NOT_FOUND(), Error::METHOD_NOT_FOUND);
        }

        $helper = new Helper();

        $isValidNumberOfParameters = $helper->isValidNumberOfParameters(
            $params,
            $reflectionMethod->getNumberOfRequiredParameters(),
            $reflectionMethod->getNumberOfParameters()
        );

        if (false == $isValidNumberOfParameters) {
            throw new Exception(Error::INVALID_PARAMS(), Error::INVALID_PARAMS);
        }

        $parametersNames = $helper->getParametersNames($reflectionMethod);

        $parameters = $helper->normalizeParameters($parametersNames, $params);

        $isValid = $helper->isValidParameters(
            $parameters,
            $parametersNames,
            $reflectionMethod->getNumberOfRequiredParameters()
        );

        if (false == $isValid) {
            throw new Exception(Error::INVALID_PARAMS(), Error::INVALID_PARAMS);
        }

        return call_user_func_array(array($this->object, $name), $parameters);
    }
}
