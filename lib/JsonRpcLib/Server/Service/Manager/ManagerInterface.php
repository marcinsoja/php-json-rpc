<?php

namespace JsonRpcLib\Server\Service\Manager;

interface ManagerInterface
{
    /**
     * @param \JsonRpcLib\Server\Service\Wrapper\WrapperInterface $service
     * @param string                                              $name
     */
    public function addService(\JsonRpcLib\Server\Service\Wrapper\WrapperInterface $service, $name);

    /**
     * @param  string                                              $name
     * @return \JsonRpcLib\Server\Service\Wrapper\WrapperInterface
     */
    public function getService($name);

    /**
     * @param \JsonRpcLib\Server\Service\Wrapper\WrapperInterface $service
     * @param string                                              $method
     * @param array                                               $params
     */
    public function execute(\JsonRpcLib\Server\Service\Wrapper\WrapperInterface $service, $method, array $params);
}
