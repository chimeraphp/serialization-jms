# Chimera - serialization-jms

[![Total Downloads]](https://packagist.org/packages/chimera/serialization-jms)
[![Latest Stable Version]](https://packagist.org/packages/chimera/serialization-jms)
[![Unstable Version]](https://packagist.org/packages/chimera/serialization-jms)

[![Build Status]](https://github.com/chimeraphp/serialization-jms/actions?query=workflow%3A%22PHPUnit%20Tests%22+branch%3A1.0.x)
[![Code Coverage]](https://codecov.io/gh/chimeraphp/serialization-jms)

> The term Chimera (_/kɪˈmɪərə/_ or _/kaɪˈmɪərə/_) has come to describe any
mythical or fictional animal with parts taken from various animals, or to
describe anything composed of very disparate parts, or perceived as wildly
imaginative, implausible, or dazzling.

There are many many amazing libraries in the PHP community and with the creation
and adoption of the PSRs we don't necessarily need to rely on full stack
frameworks to create a complex and well designed software. Choosing which
components to use and plugging them together can sometimes be a little
challenging.

The goal of this set of packages is to make it easier to do that (without
compromising the quality), allowing you to focus on the behaviour of your
software.

This package provides an adapter for [`jms/serializer`](https://github.com/schmittjoh/serializer),
allowing us to use it as a `MessageCreator` - which is responsible for converting
the user input into a message to be handled.

## Installation

Package is available on [Packagist], you can install it using [Composer].

```shell
composer require chimera/serialization-jms
```

### PHP Configuration

In order to make sure that we're dealing with the correct data, we're using `assert()`,
which is a very interesting feature in PHP but not often used. The nice thing
about `assert()` is that we can (and should) disable it in production mode so
that we don't have useless statements.

So, for production mode, we recommend you to set `zend.assertions` to `-1` in your `php.ini`.
For development you should leave `zend.assertions` as `1` and set `assert.exception` to `1`, which
will make PHP throw an [`AssertionError`](https://secure.php.net/manual/en/class.assertionerror.php)
when things go wrong.

Check the documentation for more information: https://secure.php.net/manual/en/function.assert.php

## Usage

This is how you can use the `ArrayTransformer` as a `MessageCreator`:

```php
<?php
declare(strict_types=1);

namespace MyApp;

use Chimera\ExecuteQuery;
use Chimera\MessageCreator\InputExtractor\AppendGeneratedIdentifier;
use Chimera\MessageCreator\InputExtractor\UseInputData;
use Chimera\MessageCreator\JmsSerializer\ArrayTransformer;
use JMS\Serializer\SerializerBuilder;

// We instantiate it passing a valid JMS serialiser
$messageCreator = new ArrayTransformer(
    SerializerBuilder::create()->build(),
    new AppendGeneratedIdentifier(new UseInputData())
);

// Then use it on the actions
$action = new ExecuteQuery($queryBus, $messageCreator, MyQuery::class); // considering that $queryBus is a valid instance of `ServiceBus`
$result = $action->fetch($input); // considering that $input is a valid instance of `Input`

var_dump($result);
```

## License

MIT, see [LICENSE].

[Total Downloads]: https://img.shields.io/packagist/dt/chimera/serialization-jms.svg?style=flat-square
[Latest Stable Version]: https://img.shields.io/packagist/v/chimera/serialization-jms.svg?style=flat-square
[Unstable Version]: https://img.shields.io/packagist/vpre/chimera/serialization-jms.svg?style=flat-square
[Build Status]: https://img.shields.io/github/actions/workflow/status/chimeraphp/serialization-jms/phpunit.yml?branch=1.0.x&style=flat-square
[Code Coverage]: https://codecov.io/gh/chimeraphp/serialization-jms/branch/0.5.x/graph/badge.svg
[Packagist]: http://packagist.org/packages/chimera/serialization-jms
[Composer]: http://getcomposer.org
[LICENSE]: LICENSE
