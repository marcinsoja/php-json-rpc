<?php

namespace JsonRpcLib\Server\Service\Manager;

use \JsonRpcLib\Server\Service\Wrapper\WrapperInterface;

interface ManagerInterface
{
    /**
     * @return \JsonRpcLib\Server\Service\Resolver\ResolverInterface
     */
    public function getResolver();

    /**
     * @param \JsonRpcLib\Server\Service\Wrapper\WrapperInterface $service
     * @param string                                              $name
     */
    public function addService(WrapperInterface $service, $name);

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
    public function execute(WrapperInterface $service, $method, array $params);
}
