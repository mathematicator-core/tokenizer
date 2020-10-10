<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


use Mathematicator\Numbers\SmartNumber;
use Mathematicator\Tokenizer\Tokens;

class VariableToken extends BaseToken
{

	/** @var SmartNumber */
	private $times;


	public function __construct(string $token, SmartNumber $times)
	{
		$this->setToken($token);
		$this->times = $times;
		$this->setType(Tokens::M_VARIABLE);
	}


	public function getTimes(): SmartNumber
	{
		return $this->times;
	}
}
