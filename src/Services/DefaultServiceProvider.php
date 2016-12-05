<?php
namespace Exts\DSB\Services;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\DefaultServicesProvider as SlimDefaultServiceProvider;

/**
 * Class DefaultServiceProvider
 *
 * @package Exts\DSB\Services
 */
class DefaultServiceProvider implements ServiceProviderInterface
{
    /**
     * @var array
     */
    private $defaultSettings = [
        'httpVersion' => '1.1',
        'responseChunkSize' => 4096,
        'outputBuffering' => 'append',
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => false,
        'addContentLengthHeader' => true,
        'routerCacheFile' => false,
    ];

    /**
     * @var null|SlimDefaultServiceProvider
     */
    private $defaultServiceProvider;

    /**
     * DefaultServiceProvider constructor.
     *
     * @param null|SlimDefaultServiceProvider $defaultServicesProvider
     */
    public function __construct(?SlimDefaultServiceProvider $defaultServicesProvider)
    {
        if(!isset($defaultServicesProvider)) {
            $defaultServicesProvider = new SlimDefaultServiceProvider();
        }
        $this->defaultServiceProvider = $defaultServicesProvider;
    }

    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $this->setupDefaultSettings($container);

        /** @var \Slim\Container $container */
        $this->defaultServiceProvider->register($container);
    }

    /**
     * @param Container $container
     */
    protected function setupDefaultSettings(Container $container)
    {
        $container['settings'] = array_merge($this->defaultSettings, $container['settings'] ?? []);
    }
}