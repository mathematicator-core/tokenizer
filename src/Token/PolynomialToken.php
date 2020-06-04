<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Token;


use Mathematicator\Numbers\Exception\NumberException;
use Mathematicator\Numbers\Latex\MathLatexBuilder;
use Mathematicator\Numbers\Latex\MathLatexToolkit;
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
			$power = new NumberToken(SmartNumber::of(1));
			$power->setType(Tokens::M_NUMBER)
				->setToken('1')
				->setPosition($times->getPosition() + 1);
		}

		$this->times = $times;
		$this->power = $power;
		$this->variable = $variable;

		$this->setToken(
			(string) (new MathLatexBuilder($times->getToken()))
				->multipliedBy(MathLatexToolkit::pow($variable->getToken(), $power->getToken()))
		)
			->setType(Tokens::M_POLYNOMIAL)
			->setPosition($times->getPosition());
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
