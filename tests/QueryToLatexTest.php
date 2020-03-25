<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Test;


use App\Booting;
use Mathematicator\Engine\QueryNormalizer;
use Mathematicator\Tokenizer\Tokenizer;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../../autoload.php';

class QueryToLatexTest extends TestCase
{

	/** @var Tokenizer */
	private $tokenizer;

	/** @var QueryNormalizer */
	private $queryNormalizer;


	/**
	 * @param Tokenizer $tokenizer
	 * @param QueryNormalizer $queryNormalizer
	 */
	public function __construct(Tokenizer $tokenizer, QueryNormalizer $queryNormalizer)
	{
		$this->tokenizer = $tokenizer;
		$this->queryNormalizer = $queryNormalizer;
	}


	/**
	 * @dataProvider getQueries
	 * @param string $query
	 * @param string $latex
	 */
	public function testOne(string $query, string $latex): void
	{
		$normalizedQuery = $this->queryNormalizer->normalize($query);
		$tokens = $this->tokenizer->tokenize($normalizedQuery);
		$objectTokens = $this->tokenizer->tokensToObject($tokens);

		Assert::same($latex, $this->tokenizer->tokensToLatex($objectTokens), $query . ' | ' . $normalizedQuery);
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
			['5*8', '5\cdot 8'],
			['(5+3)*2', '\left(5+3\right)\cdot 2'],
			['3/4', '\frac{3}{4}'],
			['(5+3)/2', '\frac{5+3}{2}'],
			['inf', '\infty'],
			['inf+INF', '\infty+\infty'],
			['inf^2', '{\infty}^{2}'],
			['10e6', '{10}^{6}'],
			['3.14e2', '{3.14}^{2}'],
			['x+2', 'x+2'],
			['(x^2)/2', '\frac{{x}^{2}}{2}'],
			['x^(1+2)', '{x}^{\left(1+2\right)}'],
			['x+2-x^(1+2)', 'x+2-{x}^{\left(1+2\right)}'],
			['(7/9)^6', '{\left(\frac{7}{9}\right)}^{6}'],
			['5^x', '{5}^{x}'],
			['x^7', '{x}^{7}'],
			['x^PI/2', '{x}^\frac{\pi}{2}'],
			['24E1.234', '{24}^{1.234}'],
			['5.678E9.101', '{5.678}^{9.101}'],
			['10^(1+2)', '{10}^{\left(1+2\right)}'],
			['10.159564552121254685241866835861842684', '10.159564552121254685241866835861842684'],
		];
	}
}

if (isset($_SERVER['NETTE_TESTER_RUNNER'])) {
	$di = Booting::bootForTests()->createContainer();

	(new QueryToLatexTest(
		$di->getByType(Tokenizer::class),
		$di->getByType(QueryNormalizer::class)
	))->run();
}