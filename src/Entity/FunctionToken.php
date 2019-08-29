<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


class FunctionToken extends SubToken
{

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = (string) preg_replace('/\W/', '', $name);
	}

}
