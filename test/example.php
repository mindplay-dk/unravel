<?php

use mindplay\unravel\ResolverHost;
use mindplay\unravel\resolvers\DefaultResolver;
use mindplay\unravel\resolvers\NameMapResolver;

require dirname(__DIR__) . '/vendor/autoload.php';

class HelloController
{
    public function run($world = "World")
    {
        echo "Hello, {$world}!";
    }
}

function dispatch($controller, $method = "run")
{
    $resolver = new ResolverHost([
        new NameMapResolver($_GET),
        new DefaultResolver(),
    ]);

    $reflection = new ReflectionMethod($controller, $method);

    $params = array_map([$resolver, "resolve"], $reflection->getParameters());

    call_user_func_array([$controller, $method], $params);
}

dispatch(new HelloController());
