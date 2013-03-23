<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);

$loader = require 'vendor/autoload.php';
$loader->add('JsonRpcLib', __DIR__.'/../lib');