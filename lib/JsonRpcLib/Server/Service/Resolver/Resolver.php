<?php

namespace JsonRpcLib\Server\Service\Resolver;

/**
 * Standard resolver.
 * 
 * input    | service name  | method name
 * abc      | abc           | abc
 * abc.zxy  | abc           | zxy
 * 
 */
class Resolver implements ResolverInterface
{
    /**
     * @param string $name
     * @return string
     */
    public function getServiceName($name)
    {
        return $this->getPart($name, 0);
    }

    /**
     * @param string $name
     * @return string
     */
    public function getMethodName($name)
    {
        return $this->getPart($name, 1);
    }

    /**
     * 
     * @param string $name
     * @param int $part
     * @param string $separator
     * @return string
     */
    private function getPart($name, $part, $separator = '.')
    {
        $nameParts = explode($separator, $name, 2);

        if (count($nameParts) == 2) {
            return $nameParts[$part];
        }

        return $name;
    }
}
