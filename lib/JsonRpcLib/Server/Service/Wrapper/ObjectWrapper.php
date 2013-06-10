<?php

namespace JsonRpcLib\Server\Service\Wrapper;

use \JsonRpcLib\Rpc\Error;
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
        $this->checkHasMethod($name);

        $reflectionMethod = new \ReflectionMethod(get_class($this->object), $name);

        $this->checkIsAllowedMethod($reflectionMethod);

        $params = $this->beforeExecute($reflectionMethod, $params);

        $parameters = $this->normalizeParameters($reflectionMethod, $params);

        $resultExecute = call_user_func_array(array($this->object, $name), $parameters);

        $result = $this->afterExecute($reflectionMethod, $resultExecute);

        return $result;
    }

    private function checkHasMethod($name)
    {
        if (false == method_exists($this->object, $name)) {
            throw new Exception(Error::METHOD_NOT_FOUND(), Error::METHOD_NOT_FOUND);
        }
    }

    private function checkIsAllowedMethod(\ReflectionMethod $reflectionMethod)
    {
        $isAllowed = $reflectionMethod->isPublic()
            && false == $reflectionMethod->isStatic()
            && $reflectionMethod->isUserDefined()
        ;

        if (false == $isAllowed) {
            throw new Exception(Error::METHOD_NOT_FOUND(), Error::METHOD_NOT_FOUND);
        }
    }

    private function normalizeParameters(\ReflectionMethod $reflectionMethod, array $params)
    {
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

        return $parameters;
    }

    public function beforeExecute(\ReflectionMethod $reflectionMethod, array $params)
    {
        return $params;
    }

    public function afterExecute(\ReflectionMethod $reflectionMethod, $result)
    {
        return $result;
    }
}
