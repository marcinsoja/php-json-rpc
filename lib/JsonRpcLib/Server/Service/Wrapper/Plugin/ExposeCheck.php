<?php

namespace JsonRpcLib\Server\Service\Wrapper\Plugin;

use \JsonRpcLib\Rpc\Error;
use \JsonRpcLib\Server\Exception;
use \JsonRpcLib\Server\Service\Wrapper\ExecuteEvent;

class ExposeCheck extends Annotation
{
    private $annotationName = "\JsonRpcLib\Server\Service\Wrapper\Annotation\Expose";

    public function __invoke(ExecuteEvent $e)
    {
        $reflectionMethod = $e->getReflectionMethod();

        $annotation = $this->getAnnotation($reflectionMethod, $this->annotationName);

        if (null == $annotation) {

            $errorData = array(
                "Annotation '{$this->annotationName}' is required"
            );

            throw new Exception(Error::METHOD_NOT_FOUND(), Error::METHOD_NOT_FOUND, $errorData);
        }
    }
}
