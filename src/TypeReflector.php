<?php

namespace mindplay\unravel;

use ReflectionParameter;

/**
 * This class provides a means of obtaining a parameter type-hint without auto-loading the parameter type.
 */
abstract class TypeReflector
{
    /**
     * @var string pattern for parsing an argument type from a ReflectionParameter string
     */
    const ARG_PATTERN = '/(?:\<required\>|\<optional\>)\\s+([\\w\\\\]+)/';

    /**
     * @param ReflectionParameter $param
     *
     * @return string|null fully-qualified parameter type-name (or NULL, if no type-hint was found)
     */
    public static function getTypeName(ReflectionParameter $param)
    {
        if (preg_match(TypeReflector::ARG_PATTERN, $param->__toString(), $matches) === 1) {
            return $matches[1];
        }

        return null;
    }
}
