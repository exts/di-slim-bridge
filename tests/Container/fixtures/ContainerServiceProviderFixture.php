<?php

class ContainerServiceProviderFixture implements \Pimple\ServiceProviderInterface
{
    public function register(\Pimple\Container $pimple)
    {
        $pimple['example'] = function($pimple) {
            return new ContainerServiceProviderExampleClass();
        };

        $pimple['hey'] = 'testing';

        $pimple['testing2'] = function() {
            return 'um';
        };
    }
}

class ContainerServiceProviderDIFixture implements \Pimple\ServiceProviderInterface
{
    private $example;

    public function __construct(ContainerServiceProviderExampleClass $example)
    {
        $this->example = $example;
    }

    public function register(\Pimple\Container $container)
    {
        $container['say'] = function() {
            return $this->example->hi();
        };
    }
}