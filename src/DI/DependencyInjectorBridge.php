<?php
namespace Exts\DSB\DI;

use Auryn\Injector;

/**
 * Class DependencyInjectorBridge
 *
 * @package Exts\DSB\DI
 */
class DependencyInjectorBridge implements IDependencyInjector
{
    /**
     * @var Injector
     */
    private $injector;

    /**
     * DependencyInjectorBridge constructor.
     *
     * @param Injector $injector
     */
    public function __construct(?Injector $injector)
    {
        if(!isset($injector)) {
            $injector = new Injector();
        }
        $this->injector = $injector;
    }

    /**
     * @param $instance
     *
     * @return Injector
     */
    public function share($instance)
    {
        return $this->injector->share($instance);
    }

    /**
     * @param string $class
     * @param array $args
     *
     * @return mixed
     */
    public function make(string $class, array $args = [])
    {
        return $this->injector->make($class, $args);
    }

    /**
     * @param string $original
     * @param string $alias
     *
     * @return Injector
     */
    public function alias(string $original, string $alias)
    {
        return $this->injector->alias($original, $alias);
    }

    /**
     * @param string $class
     * @param array $args
     *
     * @return Injector
     */
    public function define(string $class, array $args = [])
    {
        return $this->injector->define($class, $args);
    }
}