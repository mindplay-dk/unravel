<?php

namespace mindplay\unravel\resolvers;

use mindplay\unravel\Resolver;
use ReflectionParameter;

/**
 * Resolves parameter-names against a map where parameter-name => value
 */
class NameMapResolver implements Resolver
{
    /**
     * @var array
     */
    private $map;

    /**
     * @param array $map map where parameter-name => value
     */
    public function __construct($map)
    {
        $this->map = $map;
    }

    public function resolve(ReflectionParameter $param, $next)
    {
        if (array_key_exists($param->name, $this->map)) {
            return $this->map[$param->name];
        }

        return $next($param);
    }
}
