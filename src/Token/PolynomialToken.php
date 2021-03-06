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
	private NumberToken $times;

	private NumberToken $power;

	private VariableToken $variable;

	private bool $autoPower = false;


	/**
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
			$this->power = $power;
		} else {
			$this->power = $power;
		}

		$this->times = $times;
		$this->variable = $variable;

		$this->setToken(
			(string) (new MathLatexBuilder($times->getToken()))
				->multipliedBy(MathLatexToolkit::pow($variable->getToken(), $power->getToken())),
		)
			->setType(Tokens::M_POLYNOMIAL)
			->setPosition($times->getPosition());
	}


	public function getTimes(): NumberToken
	{
		return $this->times;
	}


	public function getPower(): NumberToken
	{
		return $this->power;
	}


	public function getVariable(): VariableToken
	{
		return $this->variable;
	}


	public function isAutoPower(): bool
	{
		return $this->autoPower;
	}
}
