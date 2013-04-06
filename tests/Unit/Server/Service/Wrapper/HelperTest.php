<?php

namespace Unit\Server\Service\Wrapper;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerNormalizeParameters
     */
    public function testNormalizeParameters($parametersNames, $params, $expected)
    {
        $helper = new \JsonRpcLib\Server\Service\Wrapper\Helper();

        $this->assertEquals($expected, $helper->normalizeParameters($parametersNames, $params));
    }

    /**
     * @dataProvider providerIsValidParameters
     */
    public function testIsValidParameters(
        $parametersNames,
        $parameters,
        $numberOfRequiredParameters,
        $expected
    )
    {
        $helper = new \JsonRpcLib\Server\Service\Wrapper\Helper();

        $this->assertEquals($expected,
            $helper->isValidParameters(
                $parameters,
                $parametersNames,
                $numberOfRequiredParameters
            )
        );
    }
    /**
     * @dataProvider providerIsValidNumberOfParameters
     */
    public function testIsValidNumberOfParameters(
        $parameters,
        $numberOfRequiredParameters,
        $numberOfParameters,
        $expected
    )
    {
        $helper = new \JsonRpcLib\Server\Service\Wrapper\Helper();

        $this->assertEquals($expected,
            $helper->isValidNumberOfParameters(
                $parameters,
                $numberOfRequiredParameters,
                $numberOfParameters
            )
        );
    }

    public function providerIsValidNumberOfParameters()
    {
        return array(
            array(
                array('a', 'b'),
                2,
                2,
                true
            ),
            array(
                array('a', 'b'),
                1,
                1,
                false
            ),
            array(
                array(),
                1,
                3,
                false
            ),
            array(
                array(),
                0,
                0,
                true
            ),
        );
    }
    public function providerIsValidParameters()
    {
        return array(
            array(
                array('a', 'b'),
                array('b'=>1),
                1,
                false
            ),
            array(
                array('a', 'b'),
                array('a'=>1),
                1,
                true
            ),
            array(
                array('a', 'b'),
                array('a'=>1),
                2,
                false
            ),
            array(
                array('a', 'b'),
                array('a'=>1, 'b'=>3),
                2,
                true
            ),
            array(
                array('a'),
                array(),
                1,
                false
            ),
            array(
                array(),
                array(),
                0,
                true
            ),
        );
    }

    public function providerNormalizeParameters()
    {
        return array(
            array(
                array('a', 'b'),
                array('1', '2'),
                array('a'=>'1', 'b'=>'2'),
            ),
            array(
                array(),
                array(),
                array(),
            ),
            array(
                array('a', 'b'),
                array('a'=>'1', '2'),
                array('a'=>'1'),
            ),
            array(
                array('a', 'b'),
                array('2', '3', 'a'=>'1'),
                array('a'=>'1'),
            ),
            array(
                array('a', 'b', 'c'),
                array('b'=>'1', 'c'=>'2', 'a'=>'3'),
                array('a'=>'3', 'b'=>'1', 'c'=>'2'),
            ),
            array(
                array('a', 'b'),
                array('b'=>'1'),
                array('b'=>'1'),
            ),
        );
    }
}
