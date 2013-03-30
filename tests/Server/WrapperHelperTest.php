<?php

class WrapperHelperTest extends PHPUnit_Framework_TestCase 
{
    /**
     * @dataProvider provider
     */
    public function testNormalizeParameters($parametersNames, $params, $expected) {

        $helper = new \JsonRpcLib\Server\Service\Wrapper\Helper();
        
        $this->assertEquals($expected, $helper->normalizeParameters($parametersNames, $params));
    }
    
    public function provider()
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
        );
    }
}
