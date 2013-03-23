<?php

namespace JsonRpcLib\Server\Service\Manager;

class Manager implements ManagerInterface
{
    private $services = array();

    public function addService($name, \JsonRpcLib\Server\Service\Provider\ProviderInterface $service)
    {
        $this->services[$name] = $service;

        return $this;
    }

    /**
     *
     * @param  type                                                       $name
     * @return \JsonRpcLib\Server\Service\Provider\ProviderInterface|null
     */
    public function getService($name)
    {
        if (array_key_exists($name, $this->services)) {
            return $this->services[$name];
        }

        return null;
    }
}
