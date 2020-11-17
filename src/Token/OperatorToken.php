<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


use function in_array;

class OperatorToken extends BaseToken
{
	private int $priority = 100;


	public function getPriority(): int
	{
		return $this->priority;
	}


	public function setPriority(string $value): IToken
	{
		$priority = [
			1 => ['+', '-'],
			2 => ['*', '/'],
			3 => ['^'],
		];

		foreach ($priority as $key => $_value) {
			if (in_array($value, $_value, true)) {
				$this->priority = $key;
				break;
			}
		}

		return $this;
	}
}
