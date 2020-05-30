<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


class FunctionManagerFacade
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


	/**
	 * @return FunctionManager|null
	 */
	public function getFunctionManager(): ?FunctionManager
	{
		return $this->functionManager;
	}


	/**
	 * @param FunctionManager $functionManager
	 */
	public function setFunctionManager(FunctionManager $functionManager): void
	{
		$this->functionManager = $functionManager;
	}
}
