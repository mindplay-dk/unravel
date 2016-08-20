<?php

namespace mindplay\unravel\resolvers;

use mindplay\unravel\Resolver;
use ReflectionParameter;

/**
 * Resolves with the default argument value, if the given parameter has a default value.
 *
 * Note that the argument does not have to be optional, as in:
 *
 *     function ($foo = 1, $bar) { ... }
 *
 * In this case, `$foo` does have a default, and will be resolved as `1`, even though
 * the parameter isn't optional. (in most cases, of course, parameters with default
 * values will also be optional.)
 */
class DefaultResolver implements Resolver
{
    public function resolve(ReflectionParameter $param, $next)
    {
        if ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        }

        return $next($param);
    }
}
