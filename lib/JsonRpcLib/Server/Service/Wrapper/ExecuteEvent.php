<?php

namespace JsonRpcLib\Server\Service\Wrapper;

use \Zend\EventManager\Event;

class ExecuteEvent extends Event
{
    /**
     * @var \ReflectionMethod
     */
    private $reflectionMethod = null;

    /**
     * @var mixed
     */
    private $result = null;

    public function setReflectionMethod(\ReflectionMethod $reflectionMethod)
    {
        $this->reflectionMethod = $reflectionMethod;
    }

    /**
     * @return \ReflectionMethod
     */
    public function getReflectionMethod()
    {
        return $this->reflectionMethod;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
}
