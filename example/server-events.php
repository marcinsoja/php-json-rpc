<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';
require_once './UserService.php';

use JsonRpcLib\Server;
use JsonRpcLib\Server\Service\Wrapper\Observable\ObjectWrapper;
use JsonRpcLib\Server\Service\Wrapper\ExecuteEvent;
use JsonRpcLib\Server\Service\Wrapper\Plugin\ResultSignature;
use JsonRpcLib\Server\Service\Wrapper\Plugin\ExposeCheck;

$server = new Server\Server();

$service = new \Example\UserService();

$wrapper = new ObjectWrapper($service);

$afterCallback = function(ExecuteEvent $e){
    $message = "\n".$e->getName().' '.  json_encode($e->getResult());
    file_put_contents('rpc.log', $message, FILE_APPEND);
};

$wrapper->getEventManager()->attach(
    'beforeExecute',
    new ExposeCheck()
);

$wrapper->getEventManager()->attach(
    'afterExecute',
    $afterCallback,
    1
);

$wrapper->getEventManager()->attach(
    'afterExecute',
    new ResultSignature($privateKey = 'abc'),
    3
);

$wrapper->getEventManager()->attach(
    'beforeExecute',
    function(ExecuteEvent $e){
        $message = "\n".$e->getName().' '.  json_encode($e->getParams());
        file_put_contents('rpc.log', $message, FILE_APPEND);
    }
);

$wrapper->getEventManager()->attach(
    'beforeExecute',
    function(ExecuteEvent $e){
//        throw new Exception("Domain exception message");
    }
);

$wrapper->getEventManager()->attach(
    'afterExecute',
    $afterCallback,
    2
);

$server->addService($wrapper);

// server-events.php?data={"jsonrpc":"2.0","method":"getId","id":1}
// server-events.php?data={"jsonrpc":"2.0","method":"getName","id":1}
$server->handle(new Server\Input\Message(new Server\Input\Data\Get('data')));
