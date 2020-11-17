<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


use Mathematicator\Numbers\SmartNumber;

class NumberToken extends BaseToken
{
	private SmartNumber $number;


	public function __construct(SmartNumber $number)
	{
		$this->number = $number;
	}


	public function getNumber(): SmartNumber
	{
		return $this->number;
	}


	public function setNumber(SmartNumber $number): self
	{
		$this->number = $number;

		return $this;
	}
}
