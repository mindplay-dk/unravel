<?php

namespace mindplay\unravel\resolvers;

use mindplay\unravel\Resolver;
use ReflectionParameter;

/**
 * This resolver always resolves with the same specific value.
 */
class ValueResolver implements Resolver
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function resolve(ReflectionParameter $param, $next)
    {
        return $this->value;
    }
}
