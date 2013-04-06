<?php

namespace Unit\Server\Service\Manager;

use \JsonRpcLib\Server\Service\Manager\Manager;
use \JsonRpcLib\Server\Service\Wrapper\ObjectWrapper;
use \JsonRpcLib\Server\Service\Wrapper\ClosureWrapper;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testAddService()
    {
        $service = new ObjectWrapper(new \stdClass());
        $manager = new Manager();

        $manager->addService($service, 'fake');

        $nullGet = $manager->getService('fake');
        $this->assertNull($nullGet);

        $fakeGet = $manager->getService('fake.abc');
        $this->assertSame($service, $fakeGet);
    }

    public function testAddCallableService()
    {
        $service = new ClosureWrapper(function(){});

        $manager = new Manager();

        $manager->addService($service, 'fake');

        $fakeGet = $manager->getService('fake');
        $this->assertSame($service, $fakeGet);
    }
}
