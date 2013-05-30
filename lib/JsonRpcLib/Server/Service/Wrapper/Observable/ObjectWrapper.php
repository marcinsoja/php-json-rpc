<?php

namespace JsonRpcLib\Server\Service\Wrapper\Observable;

class ObjectWrapper extends \JsonRpcLib\Server\Service\Wrapper\ObjectWrapper implements \Zend\EventManager\EventManagerAwareInterface
{
    /**
     *
     * @var \Zend\EventManager\EventManagerInterface
     */
    private $eventManager = null;

    public function getEventManager()
    {
        if (!$this->eventManager) {
            $this->setEventManager(new \Zend\EventManager\EventManager());
        }

        return $this->eventManager;
    }

    public function setEventManager(\Zend\EventManager\EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(array(
            __CLASS__,
            get_class($this)
        ));

        $this->eventManager = $eventManager;
    }

    public function beforeExecute(\ReflectionMethod $reflectionMethod, array $params)
    {
        $this->getEventManager()->trigger(
            __FUNCTION__,
            $this,
            array('method'=>$reflectionMethod, 'params'=>$params)
        );

        return $params;
    }

    public function afterExecute(\ReflectionMethod $reflectionMethod, $result)
    {
        $this->getEventManager()->trigger(
            __FUNCTION__,
            $this,
            array('method'=>$reflectionMethod, 'result'=>$result)
        );

        return $result;
    }
}
