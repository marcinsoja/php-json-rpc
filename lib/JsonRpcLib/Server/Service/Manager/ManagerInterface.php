<?php

namespace JsonRpcLib\Server\Service\Manager;

interface ManagerInterface
{
    /**
     * @param \JsonRpcLib\Server\Service\Provider\ProviderInterface $service
     * @param string                                                $name
     */
    public function addService(\JsonRpcLib\Server\Service\Provider\ProviderInterface $service, $name);

    /**
     * @param  string                                                $name
     * @return \JsonRpcLib\Server\Service\Provider\ProviderInterface
     */
    public function getService($name);

    /**
     * @param \JsonRpcLib\Server\Service\Provider\ProviderInterface $service
     * @param string                                                $method
     * @param array                                                 $params
     */
    public function execute(\JsonRpcLib\Server\Service\Provider\ProviderInterface $service, $method, array $params);
}
