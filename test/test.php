<?php

use Interop\Container\ContainerInterface;
use mindplay\unravel\Resolver;
use mindplay\unravel\ResolverHost;
use mindplay\unravel\resolvers\ContainerNameResolver;
use mindplay\unravel\resolvers\ContainerTypeResolver;
use mindplay\unravel\resolvers\DefaultResolver;
use mindplay\unravel\resolvers\NameMapResolver;
use mindplay\unravel\resolvers\PositionalResolver;
use mindplay\unravel\resolvers\TypeMapResolver;
use mindplay\unravel\resolvers\ValueResolver;
use mindplay\unravel\UnresolvedException;

require dirname(__DIR__) . '/vendor/autoload.php';

class MockType
{
    public $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
}

function func($param = 123, MockType $by_type)
{
}

class MockResolver implements Resolver
{
    public $passed = false;

    public function resolve(ReflectionParameter $param, $next)
    {
        $this->passed = true;

        return $next($param);
    }
}

class MockContainer implements ContainerInterface
{
    /**
     * @var array
     */
    private $values = [];

    /**
     * @param array $values
     */
    public function __construct($values)
    {
        $this->values = $values;
    }

    public function get($id)
    {
        return $this->values[$id];
    }

    public function has($id)
    {
        return isset($this->values[$id]);
    }
}

test(
    'throws for unresolved value',
    function () {
        $resolver = new ResolverHost([
            new NameMapResolver([]),
        ]);

        expect(
            "mindplay\\unravel\\UnresolvedException",
            "should throw for unresolved value",
            function () use ($resolver) {
                $resolver->resolve(new ReflectionParameter("func", "param"));
            },
            "/unresolved parameter: param/"
        );

        $param_reflection = new ReflectionParameter("func", "param");

        try {
            $resolver->resolve($param_reflection);
        } catch (UnresolvedException $e)
        {}

        ok(isset($e) && $e->getParam() === $param_reflection);
    }
);

test(
    'can compose resolver hosts',
    function () {
        $mock_1 = new MockResolver();
        $mock_2 = new MockResolver();

        $resolver = new ResolverHost([
            new ResolverHost([
                new NameMapResolver([]),
                $mock_1,
            ]),
            $mock_2,
            new ValueResolver(123),
        ]);

        eq($resolver->resolve(new ReflectionParameter("func", "param")), 123);

        ok($mock_1->passed);
        ok($mock_2->passed);
    }
);

test(
    'can resolve with default value',
    function () {
        $resolver = new ResolverHost([
            new DefaultResolver(),
            new ValueResolver(808),
        ]);

        eq($resolver->resolve(new ReflectionParameter("func", "param")), 123);

        eq($resolver->resolve(new ReflectionParameter("func", "by_type")), 808);
    }
);

test(
    'can resolve by name',
    function () {
        $resolver = new ResolverHost([
            new NameMapResolver(["param" => 456]),
            new NameMapResolver(["other" => 707]),
        ]);

        eq($resolver->resolve(new ReflectionParameter("func", "param")), 456);
    }
);

test(
    'can resolve by position',
    function () {
        $resolver = new ResolverHost([
            new PositionalResolver([1 => 909]),
            new PositionalResolver([0 => 808]),
        ]);

        eq($resolver->resolve(new ReflectionParameter("func", "param")), 808);
    }
);

test(
    'can resolve by type',
    function () {
        $resolver = new ResolverHost([
            new TypeMapResolver(["OtherType" => new MockType(808)]),
            new TypeMapResolver(["MockType" => new MockType(909)]),
        ]);

        eq($resolver->resolve(new ReflectionParameter("func", "by_type"))->value, 909);
    }
);

test(
    'can resolve using type-hints against a DI-container',
    function () {
        $container = new MockContainer([
            "MockType" => new MockType(123),
        ]);

        $resolver = new ResolverHost([
            new ContainerTypeResolver($container),
            new ValueResolver(456)
        ]);

        eq($resolver->resolve(new ReflectionParameter("func", "by_type"))->value, 123);

        eq($resolver->resolve(new ReflectionParameter("func", "param")), 456);
    }
);

test(
    'can resolve using parameter-names against a DI-container',
    function () {
        $container = new MockContainer([
            "param" => new MockType(123),
        ]);

        $resolver = new ResolverHost([
            new ContainerNameResolver($container),
            new ValueResolver(456)
        ]);

        eq($resolver->resolve(new ReflectionParameter("func", "param"))->value, 123);

        eq($resolver->resolve(new ReflectionParameter("func", "by_type")), 456);
    }
);

configure()->enableCodeCoverage(__DIR__ . '/build/clover.xml', dirname(__DIR__) . '/src');

exit(run());
