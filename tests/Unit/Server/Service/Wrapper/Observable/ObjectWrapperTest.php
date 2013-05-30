<?php

namespace Unit\Server\Service\Wrapper\Observable;

require_once __DIR__ . '/../../../../../Service.php';

use \JsonRpcLib\Server\Service\Wrapper\Observable\ObjectWrapper;

class ObjectWrapperTest extends \Unit\Server\Service\Wrapper\ObjectWrapperTest
{
    /**
     * @dataProvider providerInvalidObject
     */
    public function testInvalidObject($object)
    {
        $this->setExpectedException('\InvalidArgumentException');

        $wrapper = new ObjectWrapper($object);
    }

    /**
     * @dataProvider providerValidObject
     */
    public function testValidObject($object)
    {
        try {
            $wrapper = new ObjectWrapper($object);
        } catch (\Exception $e) {
            $this->fail();
        }
    }

    /**
     * @dataProvider providerExecuteObject
     */
    public function testExecute($object, $method, $params, $expected)
    {
        $wrapper = new ObjectWrapper($object);

        $wrapper->getEventManager()->attach('beforeExecute', new \JsonRpcLib\Server\Service\Wrapper\Plugin\Annotation());

        $result = $wrapper->execute($method, $params);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider providerFailExecuteObject
     */
    public function testFailExecute($object, $method, $params)
    {
        $wrapper = new ObjectWrapper($object);

        try {
            $wrapper->execute($method, $params);
            $wrapper->getEventManager()->attach('beforeExecute', new \JsonRpcLib\Server\Service\Wrapper\Plugin\Annotation());
            $this->fail();
        } catch (\Exception $e) {

        }
    }
}
