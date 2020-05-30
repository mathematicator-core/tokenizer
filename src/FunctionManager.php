<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


interface FunctionManager
{
	/**
	 * List of supported function names for tokenize.
	 *
	 * @return string[]
	 */
	public function getFunctionNames(): array;
}
