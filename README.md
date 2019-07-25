Robust PHP math Tokenizer
=========================

Simple library for convert math formula to array of tokens.

> Please help improve this documentation by sending a Pull request.

Install by Composer:

```
composer require mathematicator-core/tokenizer
```

Idea
----

Imagine you can:

- Convert all your math formulas to stream of tokens
- Convert user math input to LaTeX
- Solve your math problems by calculator
- Render tokens tree map for debug

How to use
----------

Inject `Tokenizer` service by DIC and tokenize your query.

```php
$tokenizer = new Tokenizer(/* some dependencies */);

// Convert math formule to array of tokens:
$tokens = $tokenizer->tokenize('(5+3)*(2/(7+3))');

// Now you can convert tokens to more useful format:
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

In `common.neon` simply define parameters:

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