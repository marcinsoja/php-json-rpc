<?php

namespace Unit\Server\Service\Resolver;

use \JsonRpcLib\Server\Service\Resolver\Resolver;

class ObjectWrapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerGetServiceName
     */
    public function testGetServiceName($name, $expected)
    {
        $resolver = new Resolver();

        $this->assertEquals($expected, $resolver->getServiceName($name));
    }

    /**
     * @dataProvider providerGetMethodName
     */
    public function testGetMethodName($name, $expected)
    {
        $resolver = new Resolver();

        $this->assertEquals($expected, $resolver->getMethodName($name));
    }

    public function providerGetServiceName()
    {
        return array(
            array(
                'service.sum', 'service'
            ),
            array(
                'Service.Sum', 'Service'
            ),
            array(
                'Service', 'Service'
            ),
            array(
                '', ''
            ),
            array(
                null, ''
            ),
        );
    }

    public function providerGetMethodName()
    {
        return array(
            array(
                'service.sum', 'sum'
            ),
            array(
                'Service.Sum', 'Sum'
            ),
            array(
                'Service', 'Service'
            ),
            array(
                '', ''
            ),
            array(
                null, ''
            ),
        );
    }

}
