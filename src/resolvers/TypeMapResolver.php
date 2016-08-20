<?php

namespace mindplay\unravel\resolvers;

use mindplay\unravel\Resolver;
use mindplay\unravel\TypeReflector;
use ReflectionParameter;

/**
 * Resolves type-hints against a map where parameter type => value
 */
class TypeMapResolver implements Resolver
{
    /**
     * @var array
     */
    private $map;

    /**
     * @param array $map map where fully-qualified class-name => parameter value
     */
    public function __construct($map)
    {
        $this->map = $map;
    }

    public function resolve(ReflectionParameter $param, $next)
    {
        $type = TypeReflector::getTypeName($param);

        if ($type !== null && array_key_exists($type, $this->map)) {
            return $this->map[$type];
        }

        return $next($param);
    }
}
