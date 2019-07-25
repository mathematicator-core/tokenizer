<?php

namespace Mathematicator\Tokenizer\Token;

use Mathematicator\Engine\MathematicatorException;
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

	/**
	 * @deprecated since 2018-10-10
	 * @param string|int|float $value
	 * @throws MathematicatorException
	 */
	public function setNumber($value): void
	{
		throw new MathematicatorException('setNumber is deprecated, use __construct().');
	}

}
