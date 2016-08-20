<?php

namespace mindplay\unravel;

use Exception;
use ReflectionParameter;

/**
 * This Exception is thrown for a failed attempt to resolve a Parameter.
 */
class UnresolvedException extends Exception
{
    /**
     * @var ReflectionParameter
     */
    private $param;

    public function __construct(ReflectionParameter $param)
    {
        parent::__construct("unresolved parameter: {$param->name}");

        $this->param = $param;
    }

    /**
     * @return ReflectionParameter
     */
    public function getParam()
    {
        return $this->param;
    }
}
