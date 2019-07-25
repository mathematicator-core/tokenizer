<?php

namespace Mathematicator\Tokenizer\Token;


use Mathematicator\Numbers\SmartNumber;

class ComplexNumberToken extends BaseToken
{

	/**
	 * @var Number
	 */
	private $realNumber;

	/**
	 * @var Number
	 */
	private $complexNumber;

	public function __construct(SmartNumber $realNumber, SmartNumber $complexNumber)
	{
		$this->realNumber = $realNumber;
		$this->complexNumber = $complexNumber;
	}

	/**
	 * @return Number
	 */
	public function getRealNumber(): SmartNumber
	{
		return $this->realNumber;
	}

	public function setRealNumber($value): void
	{
		$this->realNumber->setValue($value);
	}

	/**
	 * @return Number
	 */
	public function getComplexNumber(): SmartNumber
	{
		return $this->complexNumber;
	}

	public function setComplexNumber($value): void
	{
		$this->complexNumber->setValue($value);
	}

}