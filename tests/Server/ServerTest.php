<?php

class ServerTest extends PHPUnit_Framework_TestCase 
{
    /**
     * @dataProvider provider
     */
    public function testUse($value, $expected) {

        $in = new JsonRpcLib\Server\Input\Data\Memory($value);
        $out = new JsonRpcLib\Server\Output\Data\Memory();
        
        $inputMessage = new JsonRpcLib\Server\Input\Message($in);
        $outputMessage = new JsonRpcLib\Server\Output\Message($out);
        
        $server = new JsonRpcLib\Server\Server();
        
        $server->dispatch($inputMessage, $outputMessage);
        
        $this->assertEquals($expected, $out->data);
    }
    
    public function provider()
    {
        return array(
            array('{"jsonrpc": "2.0", "method": "subtract", "params": [42, 23], "id": 1}', 
                  '{"jsonrpc":"2.0","result":"result","id":1}'),
            array('{"jsonrpc": "2.0", "method": "subtract", "params": {"subtrahend": 23, "minuend": 42}, "id": 3}', 
                  '{"jsonrpc":"2.0","result":"result","id":3}'),
            array('{"jsonrpc": "2.0", "method": "update", "params": [1,2,3,4,5]}', 
                  null),
            array('[{"jsonrpc": "2.0", "method": "update", "params": [1,2,3,4,5]}]', 
                  null),
            array('[{"jsonrpc": "2.0", "method": "update", "params": [1,2,3,4,5]}, {"jsonrpc": "2.0", "method": "update2", "params": [1,2,3,4,5]}]', 
                  null),
            array('[{"jsonrpc": "2.0", "method": "subtract", "params": [42, 23], "id": 109}, {"jsonrpc": "2.0", "method": "update", "params": [1,2,3,4,5]}, {"jsonrpc": "2.0", "method": "update2", "params": [1,2,3,4,5]}]', 
                  '[{"jsonrpc":"2.0","result":"result","id":109}]'),
            array('[{"jsonrpc": "2.0", "method": "subtract", "params": [42, 23], "id": 1}, {"jsonrpc": "2.0", "method": "subtract", "params": {"subtrahend": 23, "minuend": 42}, "id": 3}]', 
                  '[{"jsonrpc":"2.0","result":"result","id":1},{"jsonrpc":"2.0","result":"result","id":3}]'),
        );
    }
}
