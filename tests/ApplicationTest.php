<?php

include __DIR__ . '/fixtures/ExampleController.php';
include __DIR__ . '/fixtures/ExampleMiddleware.php';

use Exts\DSB\Application;
use Exts\DSB\CallableResolver;
use Exts\DSB\Container\Container;
use Exts\DSB\DI\IDependencyInjector;
use Exts\DSB\Services\CallableResolverServiceProvider;
use Exts\DSB\Services\DefaultServiceProvider;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function testApplicationContainerServiceProviderAutoResolve()
    {
        $container = new Container();
        $container->registerService(ContainerServiceProviderFixture::class);

        $app = new Application($container);

        $getContainer = $app->getContainer();

        $this->assertTrue($getContainer['example']->hi() == 'hi');
        $this->assertTrue($container['hey'] == 'testing');
        $this->assertTrue($container['testing2'] == 'um');
    }

    public function testContainerServiceProviderAutoResolveDI()
    {
        $container = new Container();
        $container->registerService(ContainerServiceProviderDIFixture::class);

        $app = new Application($container);

        $getContainer = $app->getContainer();

        $this->assertTrue($getContainer['say'] == 'hi');
    }

    public function testApplicationCallableResolverOverrideInContainer()
    {
        $container = new Container();
        $container->registerService(CallableResolverServiceProvider::class);
        $container->registerService(DefaultServiceProvider::class);

        $app = new Application($container);

        $getContainer = $app->getContainer();

        $this->assertInstanceOf(CallableResolver::class, $getContainer['callableResolver']);
    }

    public function testIfApplicationReturnsProperDI()
    {
        $container = new Container();
        $container->registerService(CallableResolverServiceProvider::class);
        $container->registerService(DefaultServiceProvider::class);

        $app = new Application($container);

        $this->assertInstanceOf(IDependencyInjector::class, $app->getInjector());
    }

    public function testControllerAutoResolveCallable()
    {
        $container = new Container();

        $container['environment'] = \Slim\Http\Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/test'
        ]);

        $container->registerService(CallableResolverServiceProvider::class);
        $container->registerService(DefaultServiceProvider::class);

        $app = new Application($container);
        $app->get('/test', [ExampleController::class, 'index']);

        $response = $app->run(true);

        $this->assertEquals('hello', $response->getBody());

    }

    public function testControllerAutoResolveWithDI()
    {
        $container = new Container();

        $container['environment'] = \Slim\Http\Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/users/lamonte'
        ]);

        $container->registerService(CallableResolverServiceProvider::class);
        $container->registerService(DefaultServiceProvider::class);

        $app = new Application($container);
        $app->get('/users/{username}', [ExampleControllerDI::class, 'index']);

        $response = $app->run(true);

        $this->assertEquals('Hi lamonte', $response->getBody());
    }

    public function testRouteMiddlewareAutoResolve()
    {
        $container = new Container();

        $container['environment'] = \Slim\Http\Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/middleware'
        ]);

        $container->registerService(CallableResolverServiceProvider::class);
        $container->registerService(DefaultServiceProvider::class);

        $app = new Application($container);
        $app->get('/middleware', function($req, $res) { return $res; })->add(ExampleMiddleware::class);

        $response = $app->run(true);

        $this->assertEquals('hello testing worLD', $response->getBody());
    }
}
