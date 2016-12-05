<?php
namespace Exts\DSB;

use Exts\DSB\DI\IDependencyInjector;
use Slim\Interfaces\CallableResolverInterface;

/**
 * Class CallableResolver
 * @package TMI\Lib\Application
 */
class CallableResolver implements CallableResolverInterface
{
    /**
     * @var IDependencyInjector
     */
    private $injector;

    /**
     * CallableResolver constructor.
     * @param IDependencyInjector $injector
     */
    public function __construct(IDependencyInjector $injector)
    {
        $this->injector = $injector;
    }

    /**
     * @param mixed $toResolve
     * @return callable
     */
    public function resolve($toResolve) : callable
    {
        $resolved = $toResolve;

        //resolve string based callable's eg.: [ExampleClass::class, 'index']
        if(is_callable($toResolve) && is_array($toResolve) && is_string($toResolve[0])) {
            $class = $this->injector->make($toResolve[0]);
            $resolved = [$class, $toResolve[1]];
        }

        if(!is_callable($toResolve) && is_string($toResolve)) {
            // check for slim callable as "class:method"
            $callablePattern = '!^([^\:]+)\:([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)$!';
            if (preg_match($callablePattern, $toResolve, $matches)) {
                $class = $matches[1];
                $method = $matches[2];
                $resolved = [$this->injector->make($class), $method];
            } else {
                $resolved = $this->injector->make($toResolve);
            }
        }

        if(!is_callable($resolved)) {
            throw new \RuntimeException(sprintf(
                '%s is not resolvable',
                is_array($toResolve) || is_object($toResolve) ? json_encode($toResolve) : $toResolve
            ));
        }

        return $resolved;
    }
}