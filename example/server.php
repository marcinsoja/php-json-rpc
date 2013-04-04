<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

use \JsonRpcLib\Server;

class User
{
    public function getId()
    {
        return 5;
    }
    public function setData($name, $address)
    {
        return true;
    }
}

// object service
$serviceOffer = new User();

// closure service
$serviceTime = function() {
    return time();
};

// create server
$server = new Server\Server();

// bind services

// bind closure as method 'getTime'
// in: {"jsonrpc":"2.0","method":"getTime","id":1}
// out: {"jsonrpc":"2.0","result":1365105003,"id":1}
$server->addService($serviceTime, 'getTime');

// bind object as 'User' service
// in: {"jsonrpc":"2.0","method":"User.getId","id":2}
// out: {"jsonrpc":"2.0","result":5,"id":2}
// 
// in: {"jsonrpc":"2.0","method":"User.setData","params":{"address":"701 Main Street","name":"Marcin Soja"},"id":3}
// out: {"jsonrpc":"2.0","result":true,"id":3}
// OR
// in: {"jsonrpc":"2.0","method":"User.setData","params":["Marcin Soja", "701 Main Street"],"id":4}
// out: {"jsonrpc":"2.0","result":true,"id":4}

$server->addService($serviceOffer, 'User');

// testing with GET data param
// server.php?data={"jsonrpc":"2.0","method":"getTime","id":1}
$server->handle(new Server\Input\Message(new Server\Input\Data\Get('data')));

// default read data from input
// $server->handle();