<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Tests;


use Mathematicator\Numbers\SmartNumber;
use Mathematicator\Tokenizer\Token\VariableToken;
use Mathematicator\Tokenizer\Tokenizer;
use Mathematicator\Tokenizer\TokensToLatex;
use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../Bootstrap.php';

class TokensToLatexTest extends TestCase
{

	/** @var Tokenizer */
	private $tokensToLatex;


	public function __construct(Container $container)
	{
		$this->tokensToLatex = $container->getByType(TokensToLatex::class);
	}


	public function testRender(): void
	{
		$tokens = [
			new VariableToken('x', SmartNumber::of(1)),
			new VariableToken('y', SmartNumber::of(1)),
		];

		$latex = $this->tokensToLatex->render($tokens);

		Assert::same('xy', $latex);
	}
}

(new TokensToLatexTest(Bootstrap::boot()))->run();
