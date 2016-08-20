<?php

namespace mindplay\unravel\resolvers;

use Interop\Container\ContainerInterface;
use mindplay\unravel\Resolver;
use mindplay\unravel\TypeReflector;
use ReflectionParameter;

/**
 * Resolves type-hints against a DI-container using the parameter-type as the component ID.
 *
 * This Resolver provides integration with a DI-container via `container-interop`.
 *
 * @see https://github.com/container-interop/container-interop
 */
class ContainerTypeResolver implements Resolver
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
        $type = TypeReflector::getTypeName($param);

        if ($type !== null && $this->container->has($type)) {
            return $this->container->get($type);
        }

        return $next($param);
    }
}
