<?php

namespace JsonRpcLib\Server\Service\Wrapper\Observable;

use \JsonRpcLib\Server\Service\Wrapper\ExecuteEvent;
use \JsonRpcLib\Server\Service\Wrapper;
use \Zend\EventManager\EventManagerAwareInterface;
use \Zend\EventManager\EventManagerInterface;
use \Zend\EventManager\EventManager;

class ObjectWrapper extends Wrapper\ObjectWrapper implements EventManagerAwareInterface
{
    /**
     *
     * @var \Zend\EventManager\EventManagerInterface
     */
    private $eventManager = null;

    public function getEventManager()
    {
        if (!$this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }

    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(array(
            __CLASS__,
            get_class($this)
        ));

        $this->eventManager = $eventManager;
    }

    public function beforeExecute(\ReflectionMethod $reflectionMethod, array $params)
    {
        $event = new ExecuteEvent();
        $event->setReflectionMethod($reflectionMethod);
        $event->setParams($params);

        $this->getEventManager()->trigger(__FUNCTION__, $this, $event);

        return $event->getParams();
    }

    public function afterExecute(\ReflectionMethod $reflectionMethod, $result)
    {
        $event = new ExecuteEvent();
        $event->setReflectionMethod($reflectionMethod);
        $event->setResult($result);

        $this->getEventManager()->trigger(__FUNCTION__, $this, $event);

        return $event->getResult();
    }
}
