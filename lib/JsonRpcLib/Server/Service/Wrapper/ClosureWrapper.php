<?php

namespace JsonRpcLib\Server\Service\Wrapper;

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

    public function execute($name, array $params)
    {
        return call_user_func_array($this->closure, $params);
    }
}
