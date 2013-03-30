<?php

namespace JsonRpcLib;

\Phar::mapPhar('JsonRpcLib.phar');
\Phar::interceptFileFuncs();

require_once 'phar://JsonRpcLib.phar/JsonRpcLib/Tools/SplClassLoader.php';

$autoloader = new \JsonRpcLib\Tools\SplClassLoader(__NAMESPACE__, 'phar://JsonRpcLib.phar');

$autoloader->register();

__HALT_COMPILER();
