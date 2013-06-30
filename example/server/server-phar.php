<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './JsonRpcLib.phar';

use JsonRpcLib\Server;

// closure service
$serviceTime = function() {
    return time();
};

// create server
$server = new Server\Server();

// bind closure as method 'getTime'
// in: {"jsonrpc":"2.0","method":"getTime","id":1}
// out: {"jsonrpc":"2.0","result":1365105003,"id":1}
$server->addService($serviceTime, 'getTime');

// testing with GET data param
// server-phar.php?data={"jsonrpc":"2.0","method":"getTime","id":1}
$server->handle(new Server\Input\Message(new Server\Input\Data\Get('data')));
