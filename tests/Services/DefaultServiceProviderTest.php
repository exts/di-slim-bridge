<?php

use Exts\DSB\Container\Container;
use Exts\DSB\Services\CallableResolverServiceProvider;
use Exts\DSB\Services\DefaultServiceProvider;

class DefaultServiceProviderTest extends PHPUnit_Framework_TestCase
{

    public function testCallableResolverOverrideInContainer()
    {
        $container = new Container();
        $container->registerService(CallableResolverServiceProvider::class);
        $container->registerService(DefaultServiceProvider::class);

        $this->assertTrue($container['callableResolver'] instanceof \Exts\DSB\CallableResolver);
    }
}