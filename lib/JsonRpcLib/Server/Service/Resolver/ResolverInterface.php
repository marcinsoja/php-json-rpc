<?php

namespace JsonRpcLib\Server\Service\Resolver;

interface ResolverInterface
{
    /**
     * @param string $name
     * @return string
     */
    public function getServiceName($name);
    
    /**
     * @param string $name
     * @return string
     */
    public function getMethodName($name);
}
