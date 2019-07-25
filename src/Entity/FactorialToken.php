<?php

namespace Mathematicator\Tokenizer\Token;


use Mathematicator\Numbers\SmartNumber;

class FactorialToken extends BaseToken
{

	/**
	 * @var SmartNumber
	 */
	private $number;

	/**
	 * @param SmartNumber $number
	 */
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
	 * @param string $number
	 */
	public function setNumber(string $number): void
	{
		$this->number->setValue(preg_replace('/\!+$/', '', $number));
	}

}