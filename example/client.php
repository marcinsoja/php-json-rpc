<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

use JsonRpcLib\Client;

class LocalTransport {
    public function send($request) {
        
        $server = new Server\Server();
        $server->addService(function() {
            // 
        });
        $m = new Server\Output\Data\Memory();
        $in = new Server\Input\Message(new Server\Input\Data\Memory($request->toString()));
        $out = new Server\Output\Message($m);
        
        $server->dispatch($in, $out);
        
        return $m->data;
    }
}

$transport = new LocalTransport();
$client = new Client\Client($transport);
$result = $client->call('method.name', array('parem1', 'param2'));


$adapter = new Client\Adapter\Http('http://local.c/end.php');
$client = new Client\Client($adapter);