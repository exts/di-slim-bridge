<?php
namespace Exts\DSB\DI;

/**
 * Interface IDependencyInjector
 *
 * @package Exts\DSB\DI
 */
interface IDependencyInjector
{
    /**
     * @param $instance
     *
     * @return mixed
     */
    public function share($instance);

    /**
     * @param string $class
     * @param array $args
     *
     * @return mixed
     */
    public function make(string $class, array $args = []);

    /**
     * @param string $original
     * @param string $alias
     *
     * @return mixed
     */
    public function alias(string $original, string $alias);

    /**
     * @param string $class
     * @param array $args
     *
     * @return mixed
     */
    public function define(string $class, array $args = []);
}