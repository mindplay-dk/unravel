<?php

namespace mindplay\unravel;

use ReflectionParameter;

/**
 * This class implements a "middleware stack" for Resolvers.
 *
 * It also implements the Resolver interface itself, allowing for modular composition of Resolvers.
 */
class ResolverHost implements Resolver
{
    /**
     * @var Resolver[]
     */
    protected $resolvers;

    /**
     * @param Resolver[] $resolvers
     */
    public function __construct($resolvers)
    {
        $this->resolvers = $resolvers;
    }

    /**
     * Resolve a given Parameter by passing it through the resolver stack.
     *
     * @param ReflectionParameter $param
     * @param callable|null       $next resolver delegate (used internally when composing hosts)
     *
     * @return mixed
     *
     * @throws UnresolvedException if the given Parameter could not be resolved
     */
    public function resolve(ReflectionParameter $param, $next = null)
    {
        $result = call_user_func($this->delegate(0), $param);

        if ($result instanceof Unresolved) {
            if ($next) {
                return $next($param);
            }

            throw new UnresolvedException($param);
        }

        return $result;
    }

    /**
     * @param int $index
     *
     * @return callable function (ReflectionParameter $param): mixed
     */
    protected function delegate($index)
    {
        if (isset($this->resolvers[$index])) {
            return function (ReflectionParameter $param) use ($index) {
                return $this->resolvers[$index]->resolve($param, $this->delegate($index + 1));
            };
        }

        return function () {
            return new Unresolved();
        };
    }
}
