<?php

namespace mindplay\unravel;

use ReflectionParameter;

/**
 * This interface defines the role of resolvers as middleware components that resolve parameters.
 */
interface Resolver
{
    /**
     * @param ReflectionParameter $param
     * @param callable            $next delegate function to dispatch the next resolver on the stack:
     *                                  function (ReflectionParameter $param) : mixed
     * @return mixed
     */
    public function resolve(ReflectionParameter $param, $next);
}
