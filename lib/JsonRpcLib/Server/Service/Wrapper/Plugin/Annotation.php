<?php

namespace JsonRpcLib\Server\Service\Wrapper\Plugin;

use \Doctrine\Common\Annotations\AnnotationRegistry;
use \Doctrine\Common\Annotations\AnnotationReader;
use \JsonRpcLib\Rpc\Error;
use \JsonRpcLib\Server\Exception;

class Annotation
{
    public function __invoke(\Zend\EventManager\EventInterface $e)
    {
        $params = $e->getParams();
        $reflectionMethod = $params['method'];

        AnnotationRegistry::registerFile(dirname(__DIR__)."/Annotation/Service.php");

        $reader = new AnnotationReader();

        $annotation = $reader->getMethodAnnotation($reflectionMethod, '\JsonRpcLib\Server\Service\Wrapper\Annotation\Service');

        if (null == $annotation) {
            throw new Exception(Error::METHOD_NOT_FOUND(), Error::METHOD_NOT_FOUND, array('Annotation filter'));
        }
    }
}
