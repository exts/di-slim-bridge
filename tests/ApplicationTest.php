<?php

include __DIR__ . '/fixtures/ExampleController.php';
include __DIR__ . '/fixtures/ExampleMiddleware.php';

use Exts\DSB\Application;
use Exts\DSB\CallableResolver;
use Exts\DSB\Container\Container;
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

        $this->assertTrue($getContainer['callableResolver'] instanceof CallableResolver);
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

        $this->assertTrue($response->getBody() == 'hello');

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

        $this->assertTrue($response->getBody() == 'Hi lamonte');
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

        $this->assertTrue($response->getBody() == 'hello testing worLD');
    }
}
