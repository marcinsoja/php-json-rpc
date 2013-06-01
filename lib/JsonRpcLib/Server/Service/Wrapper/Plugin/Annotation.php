<?php

namespace JsonRpcLib\Server\Service\Wrapper\Plugin;

use \Doctrine\Common\Annotations\AnnotationRegistry;
use \Doctrine\Common\Annotations\AnnotationReader;

abstract class Annotation
{
    /**
     *
     * @var \Doctrine\Common\Annotations\AnnotationReader
     */
    private $reader = null;
    private $annotationNamespace = null;
    private $dirs = null;

    public function __construct()
    {
        $this->registerAutoloadNamespace($this->getAnnotationNamespace(), $this->getDirs());
    }

    public function getAnnotation($reflectionMethod, $annotationName)
    {
        $reader = $this->getReader();

        $annotation = $reader->getMethodAnnotation($reflectionMethod, $annotationName);

        return $annotation;
    }

    /**
     *
     * @param string            $namespace
     * @param string|array|null $dirs
     */
    public function registerAutoloadNamespace($namespace, $dirs = null)
    {
        AnnotationRegistry::registerAutoloadNamespace($namespace, $dirs);
    }

    public function getAnnotationNamespace()
    {
        if (null == $this->annotationNamespace) {
            $this->setAnnotationNamespace();
        }

        return $this->annotationNamespace;
    }

    public function setAnnotationNamespace($namespace = "JsonRpcLib\Server\Service\Wrapper\Annotation")
    {
        $this->annotationNamespace = $namespace;
    }

    public function getDirs()
    {
        if (null == $this->dirs) {
            $this->setDirs(dirname(__DIR__ . "/../../../../../../"));
        }

        return $this->dirs;
    }

    public function setDirs($dirs)
    {
        $this->dirs = $dirs;
    }

    private function getReader()
    {
        if (null == $this->reader) {
            $this->reader = new AnnotationReader();
        }

        return $this->reader;
    }
}
