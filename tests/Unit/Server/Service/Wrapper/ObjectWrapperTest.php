<?php

namespace Unit\Server\Service\Wrapper;

require_once __DIR__ . '/../../../../Service.php';

use \JsonRpcLib\Server\Service\Wrapper\ObjectWrapper;

class ObjectWrapperTest extends \PHPUnit_Framework_TestCase
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
            $this->fail();
        } catch (\Exception $e) {

        }
    }

    public function providerFailExecuteObject()
    {
        return array(
            array(
                new \Service(),
                'sum',
                array(1, 2, 3),
            ),
            array(
                new \Service(),
                'sum',
                array('b' => 2, 'a' => 2, 'c'=> 5)
            ),
            array(
                new \Service(),
                'subtractNumbers',
                array(7)
            ),
            array(
                new \Service(),
                'subtractNumbers',
                array('b' => 2)
            ),
            array(
                new \Service(),
                'updateDB',
                array(5, 2, 4)
            ),
            array(
                new \Service(),
                'updateDB',
                array('b' => 2, 'a' => 5, 'c'=> 6)
            ),
            array(
                new \Service(),
                'privateFn',
                array(3)
            ),
            array(
                new \Service(),
                'staticFn',
                array(3)
            ),
            array(
                new \Service(),
                '__construct',
                array()
            ),
            array(
                new \Service(),
                '__get',
                array('b')
            ),
        );
    }

    public function providerExecuteObject()
    {
        return array(
            array(
                new \Service(),
                'sum',
                array(1, 2),
                3
            ),
            array(
                new \Service(),
                'sum',
                array('b' => 2, 'a' => 2),
                4
            ),
            array(
                new \Service(),
                'subtractNumbers',
                array(7, 2),
                5
            ),
            array(
                new \Service(),
                'subtractNumbers',
                array('b' => 2, 'a' => 5),
                3
            ),
            array(
                new \Service(),
                'updateDB',
                array(5, 2),
                5
            ),
            array(
                new \Service(),
                'updateDB',
                array('b' => 2, 'a' => 5),
                5
            ),
            array(
                new \Service(),
                'updateDB',
                array('a' => 2),
                2
            ),
        );
    }

    public function providerInvalidObject()
    {
        return array(
            array(
                array()
            ),
            array(
                null
            ),
            array(
                123
            ),
            array(
                'string'
            ),
            array(
                true
            ),
        );
    }

    public function providerValidObject()
    {
        return array(
            array(
                new \ArrayObject(array())
            ),
            array(
                new \stdClass()
            ),
            array(
                new ObjectWrapperTest()
            )
        );
    }

}
