<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


use Nette\DI\CompilerExtension;

final class TokenizerExtension extends CompilerExtension
{
	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('tokenizer'))
			->setFactory(Tokenizer::class);

		$builder->addDefinition($this->prefix('tokensToObject'))
			->setFactory(TokensToObject::class);

		$builder->addDefinition($this->prefix('tokensToLatex'))
			->setFactory(TokensToLatex::class);

		$builder->addDefinition($this->prefix('functionManagerFacade'))
			->setFactory(FunctionManagerFacade::class);
	}
}
