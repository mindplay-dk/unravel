mindplay/unravel
================

This library implements a middleware stack for modular resolution of parameters
to functions, closures, methods and constructors.

[![PHP Version](https://img.shields.io/badge/php-5.4%2B-blue.svg)](https://packagist.org/packages/mindplay/unravel)
[![Build Status](https://travis-ci.org/mindplay-dk/middleman.svg)](https://travis-ci.org/mindplay-dk/unravel)
[![Code Coverage](https://scrutinizer-ci.com/g/mindplay-dk/unravel/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mindplay-dk/unravel/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mindplay-dk/unravel/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mindplay-dk/unravel/?branch=master)

You can use this library to implement invokers or factories, in Dependency Injection
containers, controller dispatchers in web-frameworks, and so forth.

This library provides components for integration with any
[container-interop](https://github.com/container-interop/container-interop)-compliant 
dependency-injection container, in the form of resolvers (middleware) that resolves
either [type-hints](src/resolvers/ContainerTypeResolver.php) or
[parameter-names](src/resolvers/ContainerNameResolver.php) against IDs in a container.

### Usage

Pretty simple, but best explained [by example](test/example.php).
