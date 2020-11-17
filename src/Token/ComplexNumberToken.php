<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


use Mathematicator\Numbers\Exception\NumberException;
use Mathematicator\Numbers\SmartNumber;

class ComplexNumberToken extends BaseToken
{
	private SmartNumber $realNumber;

	private SmartNumber $complexNumber;


	public function __construct(SmartNumber $realNumber, SmartNumber $complexNumber)
	{
		$this->realNumber = $realNumber;
		$this->complexNumber = $complexNumber;
	}


	public function getRealNumber(): SmartNumber
	{
		return $this->realNumber;
	}


	/**
	 * @throws NumberException
	 */
	public function setRealNumber(string $value): void
	{
		$this->realNumber = SmartNumber::of($value);
	}


	public function getComplexNumber(): SmartNumber
	{
		return $this->complexNumber;
	}


	/**
	 * @throws NumberException
	 */
	public function setComplexNumber(string $value): void
	{
		$this->complexNumber = SmartNumber::of($value);
	}
}
