<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

use \JsonRpcLib\Server;
use JsonRpcLib\Server\Service\Wrapper\Annotation\Expose; 

class User
{
    /**
     * @Expose
     */
    public function getId()
    {
        return 5;
    }
    
    public function getName()
    {
        return 'Kevin';
    }
}

$server = new Server\Server();

$service = new User();

$wrapper = new Server\Service\Wrapper\Observable\ObjectWrapper($service);

$afterCallback = function(Server\Service\Wrapper\ExecuteEvent $e){
    file_put_contents('rpc.log', "\n".$e->getName().' '.  json_encode($e->getResult()), FILE_APPEND);
};

$wrapper->getEventManager()->attach('beforeExecute', new Server\Service\Wrapper\Plugin\ExposeCheck());

$wrapper->getEventManager()->attach('afterExecute', $afterCallback, 1);

$wrapper->getEventManager()->attach('afterExecute', new Server\Service\Wrapper\Plugin\ResultSignature($privateKey = 'abc'), 3);

$wrapper->getEventManager()->attach('beforeExecute', function(Server\Service\Wrapper\ExecuteEvent $e){
    file_put_contents('rpc.log', "\n".$e->getName().' '.  json_encode($e->getParams()), FILE_APPEND);
});

$wrapper->getEventManager()->attach('beforeExecute', function(Server\Service\Wrapper\ExecuteEvent $e){
//    throw new Exception("Domain exception message");
});

$wrapper->getEventManager()->attach('afterExecute', $afterCallback, 2);

$server->addService($wrapper);

// server-events.php?data={"jsonrpc":"2.0","method":"getId","id":1}
// server-events.php?data={"jsonrpc":"2.0","method":"getName","id":1}
$server->handle(new Server\Input\Message(new Server\Input\Data\Get('data')));
