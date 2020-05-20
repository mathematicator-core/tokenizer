Robust PHP math Tokenizer
=========================

[![Integrity check](https://github.com/mathematicator-core/tokenizer/workflows/Integrity%20check/badge.svg)](https://github.com/mathematicator-core/tokenizer/actions?query=workflow%3A%22Integrity+check%22)
[![codecov](https://codecov.io/gh/mathematicator-core/tokenizer/branch/master/graph/badge.svg)](https://codecov.io/gh/mathematicator-core/tokenizer)
[![License: MIT](https://img.shields.io/badge/License-MIT-brightgreen.svg)](./LICENSE)

Tokenizer is a simple library used to convert math formulas to arrays of tokens.

> Please help to improve this documentation by sending a Pull request.

Install using Composer:

```
composer require mathematicator-core/tokenizer
```

Idea
----

Imagine you can:

- Convert all your math formulas to a stream of tokens
- Convert user math input to LaTeX
- Solve your math problems using a calculator
- Render the tokens tree map for debug

How to use
----------

Inject the `Tokenizer` service through DIC and tokenize your query.

```php
$tokenizer = new Tokenizer(/* some dependencies */);

// Convert math formula to an array of tokens:
$tokens = $tokenizer->tokenize('(5+3)*(2/(7+3))');

// Now you can convert tokens to a more useful format:
$objectTokens = $tokenizer->tokensToObject($tokens);

dump($objectTokens); // Return typed tokens with meta data

// Render to LaTeX
echo $tokenizer->tokensToLatex($objectTokens);

// Render to debug tree (extremely fast):
echo $tokenizer->renderTokensTree($objectTokens);
```

Configuration
-------------

Tokenizer needs your math configuration.

In `common.neon` simply define the parameters:

```yaml
parameters:
    math:
        functions:
            - sin
            - cos
            - tan
            - cotan
            - log
            - log\d*
            - ln
            - sqrt
```

Contribution
----

### Tests

All new contributions should have its unit tests in `/tests` directory.

Before you send a PR, please, check that all tests pass.

This package uses [Nette Tester](https://tester.nette.org/). You can run tests via command:
```bash
vendor/bin/tester -c tests/php.ini tests
````
