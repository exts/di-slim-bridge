<?php

require_once __DIR__ . '/fixtures/ExampleClasses.php';

use Exts\DSB\DI\DependencyInjectorBridge;
use Exts\DSB\DI\IDependencyInjector;

class DependencyInjectorBridgeTest extends PHPUnit_Framework_TestCase
{
    private $injector;

    public function __construct()
    {
        parent::__construct();

        $auryn = new Auryn\Injector();
        $this->injector = new DependencyInjectorBridge($auryn);
    }

    public function testBridgeClassToHaveProperInterface()
    {
        $this->assertTrue(is_a($this->injector, IDependencyInjector::class));
    }

    public function testClassInstantiationWithAutoWiring()
    {
        $injector = new DependencyInjectorBridge(new Auryn\Injector());
        $injector->alias(\Tests\DI\Fixture\FakeConfigInterface::class, \Tests\DI\Fixture\FakeConfig::class);

        $exampleDIClass = $injector->make(\Tests\DI\Fixture\ExampleDIClass::class);

        $this->assertTrue($exampleDIClass->showItem("bs") == null);

    }

    public function testAutoWiringFunctionalityValueShouldMatch()
    {
        $injector = new DependencyInjectorBridge(new Auryn\Injector());
        $injector->alias(\Tests\DI\Fixture\FakeConfigInterface::class, \Tests\DI\Fixture\FakeConfig::class);

        $exampleDIClass = $injector->make(\Tests\DI\Fixture\ExampleDIClass::class);
        $exampleDIClass->setItem('example', 'string data');

        $this->assertTrue($exampleDIClass->showItem('example') == 'string data');
    }
}