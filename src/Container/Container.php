<?php
namespace Exts\DSB\Container;

use Exts\DSB\DI\DependencyInjectorBridge;
use Exts\DSB\DI\IDependencyInjector;
use Interop\Container\Exception\ContainerException;
use Interop\Container\ContainerInterface;
use Pimple\Container as PimpleContainer;
use Pimple\ServiceProviderInterface;
use Slim\Exception\ContainerException as SlimContainerException;
use Slim\Exception\ContainerValueNotFoundException;

class Container extends PimpleContainer implements ContainerInterface
{
    /**
     * Container constructor.
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        parent::__construct($values);

        //setup dependency injector
        $this->setupDI();
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws ContainerValueNotFoundException  No entry was found for this identifier.
     * @throws ContainerException               Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if (!$this->offsetExists($id)) {
            throw new ContainerValueNotFoundException(sprintf('Identifier "%s" is not defined.', $id));
        }
        try {
            return $this->offsetGet($id);
        } catch (\InvalidArgumentException $exception) {
            if ($this->exceptionThrownByContainer($exception)) {
                throw new SlimContainerException(
                    sprintf('Container error while retrieving "%s"', $id),
                    null,
                    $exception
                );
            } else {
                throw $exception;
            }
        }
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has($id)
    {
        return $this->offsetExists($id);
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return $this->has($name);
    }

    /**
     * Setup dependency injector that we'll use throughout our code base
     */
    private function setupDI()
    {
        $injector = $this['injector'] ?? null;
        if(!($injector instanceof IDependencyInjector)) {
            $injector = new DependencyInjectorBridge(null);
        }

        $injector->share($injector);

        //setup dependency injector value
        $this['di'] = $this->factory(function() use($injector) {
            return $injector;
        });
    }

    /**
     * @param $provider
     * @param array $values
     *
     * @return static
     */
    public function registerService($provider, array $values = [])
    {
        if(is_object($provider)) {
            $serviceProvider = $provider;
        } else {
            try {
                $serviceProvider = $this['di']->make($provider);
            } catch(\Exception $exception) {
                throw new SlimContainerException(
                    sprintf('Container error while retrieving "%s"', $provider),
                    null,
                    $exception
                );
            }
        }

        if(!$serviceProvider instanceof ServiceProviderInterface) {
            throw new SlimContainerException(
                sprintf('Container error while registering an invalid service provider "%s"', get_class($serviceProvider))
            );
        }

        return $this->register($serviceProvider, $values);
    }

    /**
     * Tests whether an exception needs to be recast for compliance with Container-Interop.  This will be if the
     * exception was thrown by Pimple.
     *
     * @param \InvalidArgumentException $exception
     *
     * @return bool
     */
    private function exceptionThrownByContainer(\InvalidArgumentException $exception)
    {
        $trace = $exception->getTrace()[0];
        return $trace['class'] === PimpleContainer::class && $trace['function'] === 'offsetGet';
    }
}