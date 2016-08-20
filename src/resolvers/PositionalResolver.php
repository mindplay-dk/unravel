<?php

namespace mindplay\unravel\resolvers;

use mindplay\unravel\Resolver;
use ReflectionParameter;

/**
 * Resolves parameters positionally against a map where parameter index => value
 */
class PositionalResolver implements Resolver
{
    /**
     * @var array
     */
    private $params;

    /**
     * @param array $params list of parameters where parameter index => value
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    public function resolve(ReflectionParameter $param, $next)
    {
        $position = $param->getPosition();

        if (array_key_exists($position, $this->params)) {
            return $this->params[$position];
        }

        return $next($param);
    }
}
