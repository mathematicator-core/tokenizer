<?php

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
		$this->name = preg_replace('/\W/', '', $name);
	}

}
