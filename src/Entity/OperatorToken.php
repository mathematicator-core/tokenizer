<?php

namespace Mathematicator\Tokenizer\Token;

class OperatorToken extends BaseToken
{

	/**
	 * @var int
	 */
	private $priority = 100;

	/**
	 * @return int
	 */
	public function getPriority(): int
	{
		return $this->priority;
	}

	/**
	 * @param string $value
	 * @return IToken
	 */
	public function setPriority(string $value): IToken
	{
		$priority = [
			1 => ['+', '-'],
			2 => ['*', '/'],
			3 => ['^'],
		];

		foreach ($priority as $key => $_value) {
			if (\in_array($value, $_value, true)) {
				$this->priority = $key;
				break;
			}
		}

		return $this;
	}

}
