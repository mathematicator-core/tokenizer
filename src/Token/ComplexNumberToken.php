<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


use Mathematicator\Numbers\Exception\NumberException;
use Mathematicator\Numbers\SmartNumber;

class ComplexNumberToken extends BaseToken
{

	/** @var SmartNumber */
	private $realNumber;

	/** @var SmartNumber */
	private $complexNumber;


	/**
	 * @param SmartNumber $realNumber
	 * @param SmartNumber $complexNumber
	 */
	public function __construct(SmartNumber $realNumber, SmartNumber $complexNumber)
	{
		$this->realNumber = $realNumber;
		$this->complexNumber = $complexNumber;
	}


	/**
	 * @return SmartNumber
	 */
	public function getRealNumber(): SmartNumber
	{
		return $this->realNumber;
	}


	/**
	 * @param string $value
	 * @throws NumberException
	 */
	public function setRealNumber(string $value): void
	{
		$this->realNumber->setValue($value);
	}


	/**
	 * @return SmartNumber
	 */
	public function getComplexNumber(): SmartNumber
	{
		return $this->complexNumber;
	}


	/**
	 * @param string $value
	 * @throws NumberException
	 */
	public function setComplexNumber(string $value): void
	{
		$this->complexNumber->setValue($value);
	}
}
