<?php
namespace Exts\DSB;

use Exts\DSB\Container\Container;
use Exts\DSB\DI\IDependencyInjector;
use Interop\Container\ContainerInterface;
use Slim\App;

/**
 * Class Application
 *
 * @package Exts\DSB
 */
class Application extends App
{
    /**
     * Application constructor.
     *
     * @param array $container
     */
    public function __construct($container = [])
    {
        if(is_array($container)) {
            $container = new Container($container);
        }

        if (!$container instanceof ContainerInterface) {
            throw new \InvalidArgumentException('Expected a ContainerInterface');
        }

        parent::__construct($container);
    }

    /**
     * @return IDependencyInjector|null
     */
    public function getInjector() : ?IDependencyInjector
    {
        $container = $this->getContainer();

        $dependencyInjector = $container['di'] ?? null;
        if($dependencyInjector instanceof IDependencyInjector) {
            return $dependencyInjector;
        }

        return null;
    }
}