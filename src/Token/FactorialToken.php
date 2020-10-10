<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


use Mathematicator\Numbers\Exception\NumberException;
use Mathematicator\Numbers\SmartNumber;

class FactorialToken extends BaseToken
{

	/** @var SmartNumber */
	private $number;


	public function __construct(SmartNumber $number)
	{
		$this->number = $number;
	}


	public function getNumber(): SmartNumber
	{
		return $this->number;
	}


	/**
	 * @throws NumberException
	 */
	public function setNumber(string $number): void
	{
		$this->number = SmartNumber::of((string) preg_replace('/\!+$/', '', $number));
	}
}
