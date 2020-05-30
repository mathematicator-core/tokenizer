<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


use Mathematicator\Numbers\Exception\NumberException;
use Mathematicator\Numbers\SmartNumber;
use Mathematicator\Tokenizer\Tokens;

class PolynomialToken extends BaseToken
{

	/** @var NumberToken */
	private $times;

	/** @var NumberToken */
	private $power;

	/** @var VariableToken */
	private $variable;

	/** @var bool */
	private $autoPower = false;


	/**
	 * @param NumberToken $times
	 * @param NumberToken|null $power (in integer format)
	 * @param VariableToken $variable
	 * @throws NumberException
	 */
	public function __construct(NumberToken $times, ?NumberToken $power, VariableToken $variable)
	{
		if ($power === null) {
			$this->autoPower = true;
			$power = new NumberToken(new SmartNumber(0, '1'));
			$power->setType(Tokens::M_NUMBER);
			$power->setToken('1');
			$power->setPosition($times->getPosition() + 1);
		}

		$this->times = $times;
		$this->power = $power;
		$this->variable = $variable;

		$this->setToken($times->getToken() . '\cdot {' . $variable->getToken() . '}^{' . $power->getToken() . '}');
		$this->setType(Tokens::M_POLYNOMIAL);
		$this->setPosition($times->getPosition());
	}


	/**
	 * @return NumberToken
	 */
	public function getTimes(): NumberToken
	{
		return $this->times;
	}


	/**
	 * @return NumberToken
	 */
	public function getPower(): NumberToken
	{
		return $this->power;
	}


	/**
	 * @return VariableToken
	 */
	public function getVariable(): VariableToken
	{
		return $this->variable;
	}


	/**
	 * @return bool
	 */
	public function isAutoPower(): bool
	{
		return $this->autoPower;
	}
}
