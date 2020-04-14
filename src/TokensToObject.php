<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


use Mathematicator\Numbers\NumberFactory;
use Mathematicator\Tokenizer\Token\ComparatorToken;
use Mathematicator\Tokenizer\Token\EquationToken;
use Mathematicator\Tokenizer\Token\FactorialToken;
use Mathematicator\Tokenizer\Token\FunctionToken;
use Mathematicator\Tokenizer\Token\InfinityToken;
use Mathematicator\Tokenizer\Token\IToken;
use Mathematicator\Tokenizer\Token\NumberToken;
use Mathematicator\Tokenizer\Token\OperatorToken;
use Mathematicator\Tokenizer\Token\OtherToken;
use Mathematicator\Tokenizer\Token\RomanNumberToken;
use Mathematicator\Tokenizer\Token\SubToken;
use Mathematicator\Tokenizer\Token\VariableToken;
use Nette\Tokenizer\Token;

class TokensToObject
{

	/** @var NumberFactory */
	private $numberFactory;


	/**
	 * @param NumberFactory $numberFactory
	 */
	public function __construct(NumberFactory $numberFactory)
	{
		$this->numberFactory = $numberFactory;
	}


	/**
	 * @param Token[] $tokens
	 * @return IToken[]
	 */
	public function toObject(array $tokens): array
	{
		$objects = [];

		for ($iterator = 0; isset($tokens[$iterator]); $iterator++) {
			/** @var Token $token */
			$token = $tokens[$iterator];
			switch ($token->type) {
				case Tokens::M_NUMBER:
					$tokenFactory = new NumberToken($this->numberFactory->create($token->value));
					break;

				case Tokens::M_ROMAN_NUMBER:
					$tokenFactory = new RomanNumberToken(
						$this->numberFactory->create(
							(string) Helper::romanToInt($token->value)
						)
					);
					break;

				case Tokens::M_VARIABLE:
					$tokenFactory = new VariableToken(
						$token->value,
						$this->numberFactory->create('1')
					);
					break;

				case Tokens::M_FACTORIAL:
					$tokenFactory = new FactorialToken(
						$this->numberFactory->create(str_replace('!', '', $token->value))
					);
					break;

				case Tokens::M_INFINITY:
					$tokenFactory = new InfinityToken();
					break;

				case Tokens::M_OPERATOR:
					$tokenFactory = new OperatorToken();
					$tokenFactory->setPriority($token->value);
					break;

				case Tokens::M_EQUATION:
					$tokenFactory = new EquationToken();
					break;

				case Tokens::M_COMPARATOR:
					$tokenFactory = new ComparatorToken();
					break;

				case Tokens::M_LEFT_BRACKET:
					$tokenFactory = new SubToken($this);
					$iterator = $tokenFactory->setArrayTokens($tokens, $iterator);
					break;

				case Tokens::M_FUNCTION:
					$tokenFactory = new FunctionToken($this);
					$iterator = $tokenFactory->setArrayTokens($tokens, $iterator);
					$tokenFactory->setName($token->value);
					break;

				// TODO: Fix in future
				// case Tokens::M_PI:
				// $tokenFactory = new PiToken($this->numberFactory->create(M_PI), $this->numberHelper);
				// break;

				default:
					$tokenFactory = new OtherToken;
			}

			$objects[] = $tokenFactory->setToken($token->value)
				->setPosition($token->offset)
				->setType($token->type);
		}

		return $objects;
	}
}
