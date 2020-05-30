<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Tests;


use Mathematicator\Tokenizer\Tokenizer;
use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../bootstrap.php';

class QueryToLatexTest extends TestCase
{

	/** @var Tokenizer */
	private $tokenizer;


	public function __construct(Container $container)
	{
		$this->tokenizer = $container->getByType(Tokenizer::class);
	}


	/**
	 * @dataProvider getQueries
	 * @param string $normalizedQuery Query have to be already normalized! Normalizer is not a part of dependencies.
	 * @param string $expectedLatex
	 */
	public function testOne(string $expectedLatex, string $normalizedQuery): void
	{
		$tokens = $this->tokenizer->tokenize($normalizedQuery);
		$objectTokens = $this->tokenizer->tokensToObject($tokens);

		Assert::same($expectedLatex, $this->tokenizer->tokensToLatex($objectTokens), $normalizedQuery . ' | ' . $normalizedQuery);
	}


	/**
	 * @return string[][]
	 */
	public function getQueries(): array
	{
		return [
			['1', '1'],
			['256', '256'],
			['3+9-1', '3+9-1'],
			['5\cdot 8', '5*8'],
			['{\left(5+3\right)}\cdot 2', '(5+3)*2'],
			['\frac{3}{4}', '3/4'],
			['\frac{5+3}{2}', '(5+3)/2'],
			['\infty', 'INF'],
			['\infty+\infty', 'INF+INF'],
			['{\infty}^{2}', 'INF^2'],
			['{10}^{6}', '10*e6'],
			['{3.14}^{2}', '3.14*e2'],
			['x+2', 'x+2'],
			['\frac{{x}^{2}}{2}', '(x^2)/2'],
			['{x}^{\left(1+2\right)}', 'x^(1+2)'],
			['x+2-{x}^{\left(1+2\right)}', 'x+2-x^(1+2)'],
			['{\left(\frac{7}{9}\right)}^{6}', '(7/9)^6'],
			['{5}^{x}', '5^x'],
			['{x}^{7}', 'x^7'],
			['{10}^{\left(1+2\right)}', '10^(1+2)'],
			['10.159564552121254685241866835861842684', '10.159564552121254685241866835861842684'],
			['{5.678}^{9.101}', '5.678*e9.101'],
			['{24}^{1.234}', '24*e1.234'],
			['{\left(1-\frac{1}{6}\right)}^{k}\cdot \frac{1}{6}', '(1-1/6)^k*1/6'],
			['\frac{k}{2}', 'k/2'],
			['\frac{5}{2}', '5/2'],
			['{x}^{\pi}', 'x^PI'],
			['\frac{\frac{1+3+x}{4}}{2}', '((1+3+x)/4)/2'],
//			['{5}^{2}^{3}','5^2^3'],
//			['\frac{{5}^{\pi}}{2}', '5^PI/2'], // TODO
//			['\frac{{5}^{\pi}}{2}', '5^PI/2'], // TODO
//			['\frac{{x}^{\pi}}{2}', 'x^PI/2'], // TODO: returns: {x}^{\pi}\frac{\pi}{2}
		];
	}
}

(new QueryToLatexTest(Bootstrap::boot()))->run();
