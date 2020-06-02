<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Benchmarks;

use Mathematicator\Tokenizer\Tokenizer;
use Nette\Configurator;

if (\is_file($autoload = __DIR__ . '/../vendor/autoload.php')) {
	require_once $autoload;
}

/**
 * @BeforeMethods({"init"})
 */
class QueryToLatexBench
{

	/** @var Tokenizer */
	private $tokenizer;


	public function init()
	{
		$configurator = new Configurator();

		$configurator->setTempDirectory(__DIR__ . '/../temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__ . '/../src')
			->register();

		$configurator
			->addConfig(__DIR__ . '/../common.neon');

		$container = $configurator->createContainer();
		$this->tokenizer = $container->getByType(Tokenizer::class);
	}


	/**
	 * Only for comparison purposes
	 *
	 * @Revs(1000)
	 */
	public function benchComparison()
	{
		$string = (string) (123456789 + 123456789);
	}


	/**
	 * @Revs(1000)
	 */
	public function benchTokenize()
	{
		$result = $this->tokenizer->tokenize('158/ (2* 5.2)');
	}


	/**
	 * @Revs(1000)
	 */
	public function benchTokensToLatex()
	{
		$tokens = $this->tokenizer->tokenize('x+2-x^(1+2)');

		$objectTokens = $this->tokenizer->tokensToObject($tokens);

		$this->tokenizer->tokensToLatex($objectTokens);
	}
}
