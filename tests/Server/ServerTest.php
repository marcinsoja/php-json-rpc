<?php
require_once 'MockService.php';

class ServerTest extends PHPUnit_Framework_TestCase 
{
    /**
     *
     * @var \Closure 
     */
    private $closureSum = null;
    
    /**
     *
     * @var MockService
     */
    private $serviceObject = null;
    
    protected function setUp () {
        $this->closureSum = function($a, $b){
            return $a + $b;
        };
        
        $this->serviceObject = new MockService();
    }
    
    /**
     * @dataProvider provider
     */
    public function testUse($value, $expected) {

        $in = new JsonRpcLib\Server\Input\Data\Memory($value);
        $out = new JsonRpcLib\Server\Output\Data\Memory();
        
        $inputMessage = new JsonRpcLib\Server\Input\Message($in);
        $outputMessage = new JsonRpcLib\Server\Output\Message($out);
        
        $manager = new JsonRpcLib\Server\Service\Manager\Manager();
        
        $server = new JsonRpcLib\Server\Server($manager);
        
        $server->addService(new \JsonRpcLib\Server\Service\Wrapper\ClosureWrapper($this->closureSum), 'sum');
        $server->addService($this->serviceObject, 'serviceObject');
        
        $server->dispatch($inputMessage, $outputMessage);
        
        $this->assertEquals($expected, $out->data);
    }
    
    public function provider()
    {
        return array(
            array('{"jsonrpc":"2.0","method":"sum","params":[42, 23],"id":1}', 
                  '{"jsonrpc":"2.0","result":65,"id":1}'),
            
            array('{"jsonrpc":"2.0","method":"serviceObject.updateDB","params":"A","id":1}', 
                  '{"jsonrpc":"2.0","result":"A","id":1}'),
            
            array('{"jsonrpc":"2.0","method":"serviceObject.lock","id":1}', 
                  '{"jsonrpc":"2.0","result":true,"id":1}'),
            
            array('{"jsonrpc":"2.0","method":"serviceObject.sum","params":{"a":11,"b":12},"id":3}', 
                  '{"jsonrpc":"2.0","result":23,"id":3}'),
            
            array('{"jsonrpc":"2.0","method":"serviceObject.update","params":[1,2,3,4,5]}', 
                  null),
            
            array('[{"jsonrpc":"2.0","method":"serviceObject.update","params":[1,2,3,4,5,6]}]', 
                  null),
            
            array('[{"jsonrpc":"2.0","method":"serviceObject.update","params":[1,2,3,4,5]}, {"jsonrpc":"2.0","method":"serviceObject.update","params":[1,2,3,4,5]}]', 
                  null),
            
            array('[{"jsonrpc":"2.0","method":"serviceObject.subtractNumbers","params":[50, 10],"id":109}, {"jsonrpc":"2.0","method":"serviceObject.updateDB","params":[1]}, {"jsonrpc":"2.0","method":"serviceObject.updateDB","params":[1,2]}]', 
                  '[{"jsonrpc":"2.0","result":40,"id":109}]'),
            
            array('[{"jsonrpc":"2.0","method":"serviceObject.subtractNumbers","params":[60, 10],"id":1}, {"jsonrpc":"2.0","method":"serviceObject.subtractNumbers","params":{"a":10,"b":5},"id":3}]', 
                  '[{"jsonrpc":"2.0","result":50,"id":1},{"jsonrpc":"2.0","result":5,"id":3}]'),
            
            array('{"jsonrpc":"2.0","method":"sum","params":{"b":42, "a":23},"id":1}', 
                  '{"jsonrpc":"2.0","result":65,"id":1}'),
            
            array('{"jsonrpc":"2.0","method":"serviceObject.subtractNumbers","params":{"b":10, "a":60},"id":1}', 
                  '{"jsonrpc":"2.0","result":50,"id":1}'),
        );
    }
    
    /**
     * @dataProvider providerInvalidData
     */
    public function testError($value, $expected) {

        $in = new JsonRpcLib\Server\Input\Data\Memory($value);
        $out = new JsonRpcLib\Server\Output\Data\Memory();
        
        $inputMessage = new JsonRpcLib\Server\Input\Message($in);
        $outputMessage = new JsonRpcLib\Server\Output\Message($out);
        
        $manager = new JsonRpcLib\Server\Service\Manager\Manager();
        
        $server = new JsonRpcLib\Server\Server($manager);
        
        $server->addService($this->closureSum);
        $server->addService($this->serviceObject);
        $server->addService($this->serviceObject, 'solo');
        
        $server->dispatch($inputMessage, $outputMessage);
        
        $this->assertEquals($expected, $out->data);
    }
    
    public function providerInvalidData()
    {
        return array(
            array('{"jsonrpc":"2.0","method":"foobar,"params":"bar","baz]', 
                  '{"jsonrpc":"2.0","error":{"code":-32700,"message":"Parse error"},"id":null}'),
            
            array('{"jsonrpc":"2.0","method":"solo.privateFn","params":"bar","id":8}', 
                  '{"jsonrpc":"2.0","error":{"code":-32601,"message":"Method not found"},"id":8}'),
            
            array('{"jsonrpc":"2.0","method":1,"params":"bar"}',
                  '{"jsonrpc":"2.0","error":{"code":-32600,"message":"Invalid Request"},"id":null}'),
            
            array('{"jsonrpc":"2.0","method":"serviceObject.fake","params":"bar"}',
                  '{"jsonrpc":"2.0","error":{"code":-32601,"message":"Method not found"},"id":null}'),
            
            array('{"jsonrpc":"2.0","method":"solo","params":"bar"}',
                  '{"jsonrpc":"2.0","error":{"code":-32601,"message":"Method not found"},"id":null}'),
            
            array('{"jsonrpc":"2.0","method":"error"}',
                  '{"jsonrpc":"2.0","error":{"code":-32000,"message":"Server error"},"id":null}'),
            
            array('[{"jsonrpc":"2.0","method":"sum","params":[1,2,4],"id":"1"},{"jsonrpc":"2.0","method"]',
                  '{"jsonrpc":"2.0","error":{"code":-32700,"message":"Parse error"},"id":null}'),
            
            array('[]',
                  '{"jsonrpc":"2.0","error":{"code":-32600,"message":"Invalid Request"},"id":null}'),
            
            array('[1]',
                  '{"jsonrpc":"2.0","error":{"code":-32600,"message":"Invalid Request"},"id":null}'),
            
            array('[1,2,3]',
                  '[{"jsonrpc":"2.0","error":{"code":-32600,"message":"Invalid Request"},"id":null},{"jsonrpc":"2.0","error":{"code":-32600,"message":"Invalid Request"},"id":null},{"jsonrpc":"2.0","error":{"code":-32600,"message":"Invalid Request"},"id":null}]'),
            
            array('[{"jsonrpc":"2.0","method":"methodFake","params":[42, 23],"id":1},{"jsonrpc":"2.0","method":"subtractNumbers","params":[50, 10],"id":123}]', 
                  '[{"jsonrpc":"2.0","error":{"code":-32601,"message":"Method not found"},"id":1},{"jsonrpc":"2.0","result":40,"id":123}]'),
            
            array('{"jsonrpc":"2.0","method":"++++++","params":[42, 23],"id":1}', 
                  '{"jsonrpc":"2.0","error":{"code":-32600,"message":"Invalid Request"},"id":null}'),
            
            array('{"jsonrpc":"2.0","method":"sum","params":[42, 23, 48],"id":1}', 
                  '{"jsonrpc":"2.0","error":{"code":-32602,"message":"Invalid params"},"id":1}'),
            
            array('{"jsonrpc":"2.0","method":"serviceObject.subtractNumbers","params":{"b":10, "a":60, "c":5},"id":1}', 
                  '{"jsonrpc":"2.0","error":{"code":-32602,"message":"Invalid params"},"id":1}'),
            
            array('{"jsonrpc":"2.0","method":"serviceObject.subtractNumbers","params":{"b":10},"id":1}', 
                  '{"jsonrpc":"2.0","error":{"code":-32602,"message":"Invalid params"},"id":1}'),
        );
    }
}
