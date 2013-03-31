<?php

namespace JsonRpcLib\Server\Service\Wrapper;

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
            throw new \JsonRpcLib\Server\Exception(
                \JsonRpcLib\Server\Output\Error::METHOD_NOT_FOUND(),
                \JsonRpcLib\Server\Output\Error::METHOD_NOT_FOUND
            );
        }

        $reflectionMethod = new \ReflectionMethod(get_class($this->object), $name);
        
        if(false == $reflectionMethod->isPublic()) {
            throw new \JsonRpcLib\Server\Exception(
                \JsonRpcLib\Server\Output\Error::METHOD_NOT_FOUND(),
                \JsonRpcLib\Server\Output\Error::METHOD_NOT_FOUND
            );
        }

        $helper = new Helper();

        if (false == $helper->isValidParameters($reflectionMethod, $params)) {
            throw new \JsonRpcLib\Server\Exception(
                \JsonRpcLib\Server\Output\Error::INVALID_PARAMS(),
                \JsonRpcLib\Server\Output\Error::INVALID_PARAMS
            );
        }

        $parameters = $helper->normalizeParameters(
            $helper->getParametersNames($reflectionMethod),
            $params
        );

        if (false == $helper->isValidParameters($reflectionMethod, $parameters)) {
            throw new \JsonRpcLib\Server\Exception(
                \JsonRpcLib\Server\Output\Error::INVALID_PARAMS(),
                \JsonRpcLib\Server\Output\Error::INVALID_PARAMS
            );
        }

        return call_user_func_array(array($this->object, $name), $parameters);
    }
}
