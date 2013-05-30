<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

use \JsonRpcLib\Server;
use JsonRpcLib\Server\Service\Wrapper\Annotation\Service; 

class User
{
    /**
     * @Service
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

$wrapper->getEventManager()->attach('beforeExecute', new Server\Service\Wrapper\Plugin\Annotation());
$wrapper->getEventManager()->attach('beforeExecute', function(\Zend\EventManager\EventInterface $e){
    file_put_contents('rpc.log', "\n".$e->getName().' '.  json_encode($e->getParam('params')), FILE_APPEND);
});
$wrapper->getEventManager()->attach('beforeExecute', function(\Zend\EventManager\EventInterface $e){
    throw new Exception("Domain exception message");
});
$wrapper->getEventManager()->attach('afterExecute', function(\Zend\EventManager\EventInterface $e){
    file_put_contents('rpc.log', "\n".$e->getName().' '.$e->getParam('result'), FILE_APPEND);
});

$server->addService($wrapper);

// server-events.php?data={"jsonrpc":"2.0","method":"getId","id":1}
// server-events.php?data={"jsonrpc":"2.0","method":"getName","id":1}
$server->handle(new Server\Input\Message(new Server\Input\Data\Get('data')));
