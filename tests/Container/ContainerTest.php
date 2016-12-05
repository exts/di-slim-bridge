<?php

require_once __DIR__ . '/fixtures/ContainerServiceProviderExampleClass.php';

use Exts\DSB\Container\Container;

class DSBContainerTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultContainerAssignment()
    {
        $container = new Container(['example' => 'data']);

        $this->assertTrue($container['example'] == 'data');
    }

    public function testContainerServiceProviderAutoResolve()
    {
        $container = new Container();
        $container->registerService(ContainerServiceProviderFixture::class);

        $test = $container['example'];

        $this->assertTrue($test->hi() == 'hi');
        $this->assertTrue($container['hey'] == 'testing');
        $this->assertTrue($container['testing2'] == 'um');
    }

    public function testContainerServiceProviderAutoResolveDI()
    {
        $container = new Container();
        $container->registerService(ContainerServiceProviderDIFixture::class);

        $this->assertTrue($container['say'] == 'hi');
    }
}