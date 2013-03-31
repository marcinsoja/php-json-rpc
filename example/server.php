<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'Service/Offer.php';
require_once '../vendor/autoload.php';

$serviceOffer = new \Example\Service\Offer();

$server = new \JsonRpcLib\Server\Server();

$server->addService(function() {
    return time();
}, 'getTime');

$server->addService($serviceOffer, 'Offer');

// server.php?data={"jsonrpc":"2.0","method":"Offer.sum","params":[5,5],"id":4}
$server->handle(new \JsonRpcLib\Server\Input\Message(new \JsonRpcLib\Server\Input\Data\Get('data')));
