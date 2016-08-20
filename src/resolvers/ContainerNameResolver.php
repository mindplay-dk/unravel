<?php

namespace mindplay\unravel\resolvers;

use Interop\Container\ContainerInterface;
use mindplay\unravel\Resolver;
use ReflectionParameter;

/**
 * Resolves parameter names against a DI-container using the parameter-name as the component ID.
 *
 * This Resolver provides integration with a DI-container via `container-interop`.
 *
 * @see https://github.com/container-interop/container-interop
 */
class ContainerNameResolver implements Resolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve(ReflectionParameter $param, $next)
    {
        if ($this->container->has($param->name)) {
            return $this->container->get($param->name);
        }

        return $next($param);
    }
}
