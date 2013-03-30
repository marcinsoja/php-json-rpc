<?php

namespace JsonRpcLib;

require_once __DIR__ . '/Tools/SplClassLoader.php';

$autoloader = new \JsonRpcLib\Tools\SplClassLoader(__NAMESPACE__, dirname(__DIR__));
$autoloader->register();
