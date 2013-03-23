<?php

namespace JsonRpcLib\Server\Service\Manager;

interface ManagerInterface
{
    /**
     * @param string                                                $name
     * @param \JsonRpcLib\Server\Service\Provider\ProviderInterface $service
     */
    public function addService($name, \JsonRpcLib\Server\Service\Provider\ProviderInterface $service);

    /**
     * @param  string                                                $name
     * @return \JsonRpcLib\Server\Service\Provider\ProviderInterface
     */
    public function getService($name);
}
