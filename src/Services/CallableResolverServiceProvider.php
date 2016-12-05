<?php
namespace Exts\DSB\Services;

use Exts\DSB\CallableResolver;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class CallableResolverProvider
 *
 * @package TMI\Services
 */
class CallableResolverServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        //setup callable resolver
        $container['callableResolver'] = function($container) {
            return new CallableResolver($container['di']);
        };
    }
}