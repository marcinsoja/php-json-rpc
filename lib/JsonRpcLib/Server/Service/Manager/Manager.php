<?php

namespace JsonRpcLib\Server\Service\Manager;

class Manager implements ManagerInterface
{
    private $services = array();

    /**
     *
     * @param  \JsonRpcLib\Server\Service\Wrapper\WrapperInterface $service
     * @param  string                                              $name
     * @return \JsonRpcLib\Server\Service\Manager\Manager
     */
    public function addService(\JsonRpcLib\Server\Service\Wrapper\WrapperInterface $service, $name = null)
    {
        if (null === $name) {
            $name = __CLASS__;
        }

        $this->services[$name] = $service;

        return $this;
    }

    /**
     *
     * @param  type                                                     $name
     * @return \JsonRpcLib\Server\Service\Wrapper\WrapperInterface|null
     */
    public function getService($name)
    {
        $serviceName = $this->getServiceName($name);

        if (array_key_exists($serviceName, $this->services)) {
            $service = $this->services[$serviceName];

            if ($serviceName == $name && !$service instanceof \JsonRpcLib\Server\Service\Wrapper\CallableInterface) {
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
    public function execute(\JsonRpcLib\Server\Service\Wrapper\WrapperInterface $service, $name, array $params)
    {
        $methodName = $this->getMethodName($name);

        return $service->execute($methodName, $params);
    }

    private function getServiceName($name)
    {
        return $this->getPart($name, 0);
    }

    private function getMethodName($name)
    {
        return $this->getPart($name, 1);
    }

    private function getPart($name, $part, $separator = '.')
    {
        $nameParts = explode($separator, $name, 2);

        if (count($nameParts) == 2) {
            return $nameParts[$part];
        }

        return $name;
    }
}
