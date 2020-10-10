<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


final class FunctionManagerFacade
{

	/** @var FunctionManager|null */
	private $functionManager;


	/**
	 * @return string[]
	 */
	public function getFunctionNames(): array
	{
		if ($this->functionManager === null) {
			return [];
		}

		return $this->functionManager->getFunctionNames();
	}


	public function getFunctionManager(): ?FunctionManager
	{
		return $this->functionManager;
	}


	public function setFunctionManager(FunctionManager $functionManager): void
	{
		$this->functionManager = $functionManager;
	}
}
