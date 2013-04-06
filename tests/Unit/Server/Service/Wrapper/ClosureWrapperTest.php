<?php

namespace Unit\Server\Service\Wrapper;

use \JsonRpcLib\Server\Service\Wrapper\ClosureWrapper;

class ClosureWrapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerExecuteClosure
     */
    public function testExecute($closure, $params, $expected)
    {
        $wrapper = new ClosureWrapper($closure);

        $result = $wrapper->execute(null, $params);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider providerFailExecuteClosure
     */
    public function testFailExecute($closure, $params)
    {
        $wrapper = new ClosureWrapper($closure);

        try {
            $wrapper->execute(null, $params);
            $this->fail();
        } catch (\Exception $e) {

        }
    }

    public function providerFailExecuteClosure()
    {
        return array(
            array(
                function($a, $b) {
                    return $a + $b;
                },
                array(1)
            ),
            array(
                function($a, $b) {
                    return $a + $b;
                },
                array('b' => 2)
            ),
            array(
                function($a, $b) {
                    return $a + $b;
                },
                array('b' => 2, 'a' => 3, 'c' => 12)
            ),
            array(
                function($a, $b) {
                    return $a + $b;
                },
                array(2,3,4)
            ),
            array(
                function($a, $b) {
                    return $a + $b;
                },
                array()
            ),
            array(
                function() {
                    return true;
                },
                array(1)
            ),
            array(
                function() {
                    return true;
                },
                array('a'=>1)
            )
        );
    }

    public function providerExecuteClosure()
    {
        return array(
            array(
                function($a, $b) {
                    return $a + $b;
                },
                array(1, 2),
                3
            ),
            array(
                function($a, $b) {
                    return $a + $b;
                },
                array('b' => 2, 'a' => 2),
                4
            ),
            array(
                function($a, $b) {
                    return $a - $b;
                },
                array(7, 2),
                5
            ),
            array(
                function($a, $b) {
                    return $a - $b;
                },
                array('b' => 2, 'a' => 5),
                3
            ),
            array(
                function($a, $b = null) {
                    return $a;
                },
                array(5, 2),
                5
            ),
            array(
                function($a, $b = null) {
                    return $a;
                },
                array('b' => 2, 'a' => 5),
                5
            ),
            array(
                function($a, $b = null) {
                    return $a;
                },
                array('a' => 2),
                2
            ),
        );
    }

}
