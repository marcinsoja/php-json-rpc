# PHP JSON-RPC Lib

##Build Status

[![Build Status](https://travis-ci.org/marcinsoja/php-json-rpc.png?branch=master)](https://travis-ci.org/marcinsoja/php-json-rpc)

## Version

The current version is considered Beta. This means that it is ready enough to test and use, but beware that you should update frequently.

As this software is BETA, Use at your own risk!

## About

JSON-RPC Server/Client for PHP 5.3

## Installation

### PHAR

[Download](https://s3-eu-west-1.amazonaws.com/php-json-rpc/JsonRpcLib.phar) the latest phar build. Then, just require the phar in your code!

`require_once '/path/to/JsonRpcLib.phar';`

### Composer

Add a `composer.json` file to your project with the following:

```javascript
{
    "require": {
        "JsonRpcLib/JsonRpcLib": "*"
    }
}
```

Then, inside that folder, just run `php composer.phar install`.

Then, in your code, just use the composer autoloader:

`require_once 'vendor/autoload.php';`

That's it!

## Usage - Server

Most use-cases can simply use the root `JsonRpcLib\Server\Server` class.


```php
<?php

require_once 'vendor/autoload.php';

$server = new \JsonRpcLib\Server\Server();

$server->addService($service);

$server->handle();

```

### Service Closure

```php
<?php

require_once 'vendor/autoload.php';

$server = new \JsonRpcLib\Server\Server();

$server->addService(function($a, $b) {
    return $a + $b;
}, 'sum'); // sum

$server->handle();

```


### Service Object

```php
<?php

require_once 'vendor/autoload.php';

class MockService {
    public function sum($a, $b) {
        return $a + $b;
    }
}

$service = new MockService();

$server = new \JsonRpcLib\Server\Server();
$server->addService($service); // sum
$server->handle();

```

or

```php
<?php

// ...

$server->addService($service, 'math'); // math.sum

// ...

```

### Multiple services

```php
<?php

// ...

$server->addService($service, 'math'); // math.sum

$server->addService(function($data){
    // update data
}, 'update'); // update

$server->addService(function($a, $b){
    return $a + $b;
}, 'sum'); // sum

$server->addService($userService, 'user'); // user.getName, user.getSurname, user.*

// ...

```

### Full configuration

```php
<?php

$in = new JsonRpcLib\Server\Input\Data\Input();
$out = new JsonRpcLib\Server\Output\Data\Output();

$inputMessage = new JsonRpcLib\Server\Input\Message($in);
$outputMessage = new JsonRpcLib\Server\Output\Message($out);

$resolver = new \JsonRpcLib\Server\Service\Resolver\Resolver();

$manager = new JsonRpcLib\Server\Service\Manager\Manager($resolver);

$server = new JsonRpcLib\Server\Server($manager);

$server->addService(new \JsonRpcLib\Server\Service\Wrapper\ClosureWrapper($closure), 'getYear');
$server->addService(new \JsonRpcLib\Server\Service\Wrapper\ObjectWrapper($object), 'getName');

$server->dispatch($inputMessage, $outputMessage);

```

## Design Goals

### Well Designed

The code will use industry standard design patterns as well as follow guidelines for clean and testable code.

### Well Tested

That means that the code should be well covered by unit tests.

### Easy To Install

PHP JsonRpcLib will support two install methods. The first method is a single file PHAR archive. The second is support via Composer.

### Easy To Extend

The library should be very easy to extend and add new functionality.

