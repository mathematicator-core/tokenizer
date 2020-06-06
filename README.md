<h1 align="center">
    PHP Math Tokenizer
</h1>

<p align="center">
    <a href="https://mathematicator.com" target="_blank">
        <img src="https://avatars3.githubusercontent.com/u/44620375?s=100&v=4">
    </a>
</p>

[![Integrity check](https://github.com/mathematicator-core/tokenizer/workflows/Integrity%20check/badge.svg)](https://github.com/mathematicator-core/tokenizer/actions?query=workflow%3A%22Integrity+check%22)
[![codecov](https://codecov.io/gh/mathematicator-core/tokenizer/branch/master/graph/badge.svg)](https://codecov.io/gh/mathematicator-core/tokenizer)
[![Latest Stable Version](https://poser.pugx.org/mathematicator-core/tokenizer/v/stable)](https://packagist.org/packages/mathematicator-core/tokenizer)
[![Latest Unstable Version](https://poser.pugx.org/mathematicator-core/tokenizer/v/unstable)](https://packagist.org/packages/mathematicator-core/tokenizer)
[![License: MIT](https://img.shields.io/badge/License-MIT-brightgreen.svg)](./LICENSE)
[![PHPStan Enabled](https://img.shields.io/badge/PHPStan-enabled%20L8-brightgreen.svg?style=flat)](https://phpstan.org/)

Mathematicator Tokenizer is a simple library for converting string math formulas to
a stream of tokens with LaTeX support.

## Installation

```
composer require mathematicator-core/tokenizer
```

## Features

- Convert all your math formulas to a stream of tokens
- Convert user math input to LaTeX
- Render the tokens tree map for debug

## Usage

Inject the `Tokenizer` service via [DI](https://doc.nette.org/en/3.0/dependency-injection)
and tokenize your query.

```php
use \Mathematicator\Tokenizer\Tokenizer;

$tokenizer = new Tokenizer(/* some dependencies */);

// Convert math formula to an array of tokens:
$tokens = $tokenizer->tokenize('(5+3)*(2/(7+3))');

// Now you can convert tokens to a more useful format:
$objectTokens = $tokenizer->tokensToObject($tokens);

var_dump($objectTokens); // Return typed tokens with meta data

// Render to LaTeX
echo $tokenizer->tokensToLatex($objectTokens);

// Render to debug tree (extremely fast):
echo $tokenizer->renderTokensTree($objectTokens);
```

### Configuration

The tokenizer uses automatic configuration based on DI.
Just use the DI container and the service will be fully available.

## Mathematicator Framework tools structure

The biggest advantage is that you can choose which layer best fits
your needs and start build on the top of it, immediately, without the need
to create everything by yourself. Our tools are tested for bugs
and tuned for performance, so you can save a significant amount
of your time, money, and effort.

Framework tend to be modular as much as possible, so you should be able
to create an extension on each layer and its sublayers.

**Mathematicator framework layers** ordered from the most concrete
one to the most abstract one:

<table>
    <tr>
        <td>
            <b>
            <a href="https://github.com/mathematicator-core/search">
                Search
            </a>
            </b>
        </td>
        <td>
            Modular search engine layer that calls its sublayers
            and creates user interface.
        </td>
    </tr>
    <tr>
        <td>
            <b>
            <a href="https://github.com/mathematicator-core/vizualizator">
                Vizualizator
            </a>
            </b>
        </td>
        <td>
            Elegant graphic visualizer that can render to
            SVG, PNG, JPG and Base64.<br />
            <u>Extensions:</u>
            <b>
            <a href="https://github.com/mathematicator-core/mandelbrot-set">
                Mandelbrot set generator
            </a>
            </b>
        </td>
    </tr>
    <tr>
        <td>
            <b>
            <a href="https://github.com/mathematicator-core/calculator">
                Calculator
            </a>
            </b>
        </td>
        <td>
            Modular advance calculations layer.
            <br />
            <u>Extensions:</u>
            <b>
            <a href="https://github.com/mathematicator-core/integral-solver">
                Integral Solver
            </a>,
            <a href="https://github.com/mathematicator-core/statistic">
                Statistics
            </a>
            </b>
        </td>
    </tr>
    <tr>
        <td>
            <b>
            <a href="https://github.com/mathematicator-core/engine">
                Engine
            </a>
            </b>
        </td>
        <td>
            Core logic layer that maintains basic controllers,
            DAOs, translator, common exceptions, routing etc.
        </td>
    </tr>
    <tr>
        <td>
            <b>
            <a href="https://github.com/mathematicator-core/tokenizer">
                Tokenizer
            </a>
            </b>
        </td>
        <td>
            Tokenizer that can convert string (user input / LaTeX) to numbers
            and operators.
        </td>
    </tr>
    <tr>
        <td>
            <b>
            <a href="https://github.com/mathematicator-core/numbers">
                Numbers
            </a>
            </b>
        </td>
        <td>
            Fast & secure storage for numbers with arbitrary precision.
            It supports Human string and LaTeX output and basic conversions.
        </td>
    </tr>
</table>

**Third-party packages:**

⚠️ Not guaranteed!

<table>
    <tr>
        <td>
            <b>
            <a href="https://github.com/cothema/math-php-api">
                REST API
            </a>
            </b>
        </td>
        <td>
            Install the whole pack as a REST API service
            on your server (Docker ready) or
            access it via public cloud REST API.
        </td>
    </tr>
</table>

## Contribution

> Please help to improve this documentation by sending a Pull request.

### Tests

All new contributions should have its unit tests in `/tests` directory.

Before you send a PR, please, check all tests pass.

This package uses [Nette Tester](https://tester.nette.org/).
You can run tests via command:
```bash
composer test
````

For benchmarking, we use [phpbench](https://github.com/phpbench/phpbench).
You can run benchmarks this way:
```bash
composer global require phpbench/phpbench @dev # only the first time
phpbench run
````

Before PR, please run complete code check via command:
```bash
composer cs:install # only first time
composer fix # otherwise pre-commit hook can fail
````
