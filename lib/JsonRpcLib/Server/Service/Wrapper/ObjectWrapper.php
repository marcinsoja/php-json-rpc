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

    public function execute($name, array $params)
    {
        if (false == method_exists($this->object, $name)) {
            throw new \JsonRpcLib\Server\Exception(
                \JsonRpcLib\Server\Output\Error::METHOD_NOT_FOUND(),
                \JsonRpcLib\Server\Output\Error::METHOD_NOT_FOUND
            );
        }

        $reflectionMethod = new \ReflectionMethod(get_class($this->object), $name);
        $methodParams = $reflectionMethod->getParameters();

        $requiredParams = 0;

        foreach ($methodParams as $param) {
            if (false == $param->isOptional()) {
                $requiredParams++;
            }
        }

        if (count($params) < $requiredParams || count($params) > count($methodParams)) {
            throw new \JsonRpcLib\Server\Exception(
                \JsonRpcLib\Server\Output\Error::INVALID_PARAMS(),
                \JsonRpcLib\Server\Output\Error::INVALID_PARAMS
            );
        }

        // @todo named params
        return call_user_func_array(array($this->object, $name), $params);
    }
}
