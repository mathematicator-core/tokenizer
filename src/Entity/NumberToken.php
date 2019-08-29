<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


use Mathematicator\Numbers\SmartNumber;

class NumberToken extends BaseToken
{

	/**
	 * @var SmartNumber
	 */
	private $number;

	public function __construct(SmartNumber $number)
	{
		$this->number = $number;
	}

	/**
	 * @return SmartNumber
	 */
	public function getNumber(): SmartNumber
	{
		return $this->number;
	}

}
