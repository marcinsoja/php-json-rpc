<?php

namespace JsonRpcLib\Server\Service\Manager;

use \JsonRpcLib\Server\Service\Wrapper\WrapperInterface;
use \JsonRpcLib\Server\Service\Wrapper\CallableInterface;
use \JsonRpcLib\Server\Service\Resolver\Resolver;
use \JsonRpcLib\Server\Service\Resolver\ResolverInterface;

class Manager implements ManagerInterface
{
    /**
     *
     * @var \JsonRpcLib\Server\Service\Resolver\ResolverInterface
     */
    private $resolver = null;

    /**
     * @var array
     */
    private $services = array();

    /**
     * @param \JsonRpcLib\Server\Service\Resolver\ResolverInterface $resolver
     */
    public function __construct(ResolverInterface $resolver = null)
    {
        $this->resolver = $resolver;
    }

    /**
     *
     * @param  \JsonRpcLib\Server\Service\Wrapper\WrapperInterface $service
     * @param  string                                              $name
     * @return \JsonRpcLib\Server\Service\Manager\Manager
     */
    public function addService(WrapperInterface $service, $name = null)
    {
        if (null === $name) {
            $name = __CLASS__;
        }

        $this->services[$name] = $service;

        return $this;
    }

    /**
     *
     * @param  string                                                   $name
     * @return \JsonRpcLib\Server\Service\Wrapper\WrapperInterface|null
     */
    public function getService($name)
    {
        $serviceName = $this->getResolver()->getServiceName($name);

        $service = null;

        if (array_key_exists($serviceName, $this->services)) {
            $service = $this->services[$serviceName];

            if ($serviceName == $name && !$service instanceof CallableInterface) {
                $service = null;
            }
        } elseif (array_key_exists(__CLASS__, $this->services)) {
            $service = $this->services[__CLASS__];
        }

        return $service;
    }

    /**
     * @param \JsonRpcLib\Server\Service\Wrapper\WrapperInterface $service
     * @param string                                              $method
     * @param array                                               $params
     */
    public function execute(WrapperInterface $service, $name, array $params)
    {
        $methodName = $this->getResolver()->getMethodName($name);

        return $service->execute($methodName, $params);
    }

    /**
     * @return \JsonRpcLib\Server\Service\Resolver\ResolverInterface
     */
    public function getResolver()
    {
        if (null == $this->resolver) {
            $this->resolver = new Resolver();
        }

        return $this->resolver;
    }
}
