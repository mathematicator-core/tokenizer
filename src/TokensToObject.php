<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


use App\VikiTron\Model\Number\NumberHelper;
use Mathematicator\Numbers\NumberFactory;
use Mathematicator\Tokenizer\Token\BaseToken;
use Mathematicator\Tokenizer\Token\ComparatorToken;
use Mathematicator\Tokenizer\Token\EquationToken;
use Mathematicator\Tokenizer\Token\FactorialToken;
use Mathematicator\Tokenizer\Token\FunctionToken;
use Mathematicator\Tokenizer\Token\InfinityToken;
use Mathematicator\Tokenizer\Token\NumberToken;
use Mathematicator\Tokenizer\Token\OperatorToken;
use Mathematicator\Tokenizer\Token\OtherToken;
use Mathematicator\Tokenizer\Token\RomanNumberToken;
use Mathematicator\Tokenizer\Token\SubToken;
use Mathematicator\Tokenizer\Token\VariableToken;
use Nette\Tokenizer\Token;

class TokensToObject
{

	/**
	 * @var NumberFactory
	 */
	private $numberFactory;

	/**
	 * @var NumberHelper
	 */
	private $numberHelper;

	/**
	 * @param NumberFactory $numberFactory
	 * @param NumberHelper $numberHelper
	 */
	public function __construct(NumberFactory $numberFactory, NumberHelper $numberHelper)
	{
		$this->numberFactory = $numberFactory;
		$this->numberHelper = $numberHelper;
	}

	/**
	 * @param Token[] $tokens
	 * @return BaseToken[]
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
							(string) NumberHelper::romanToInt($token->value)
						)
					);
					break;

				case Tokens::M_VARIABLE:
					$tokenFactory = new VariableToken(
						$token->value,
						$this->numberFactory->create(1)
					);
					break;

				case Tokens::M_FACTORIAL:
					$tokenFactory = new FactorialToken(
						$this->numberFactory->create(
							str_replace('!', '', $token->value)
						)
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
					$tokenFactory = new SubToken(new TokensToObject($this->numberFactory, $this->numberHelper));
					$iterator = $tokenFactory->setArrayTokens($tokens, $iterator);
					break;

				case Tokens::M_FUNCTION:
					$tokenFactory = new FunctionToken(new TokensToObject($this->numberFactory, $this->numberHelper));
					$iterator = $tokenFactory->setArrayTokens($tokens, $iterator);
					$tokenFactory->setName($token->value);
					break;

				// TODO: Fix in future
				//case Tokens::M_PI:
				//	$tokenFactory = new PiToken($this->numberFactory->create(M_PI), $this->numberHelper);
				//	break;

				default:
					$tokenFactory = new OtherToken();
			}

			$tokenFactory->setToken($token->value);
			$tokenFactory->setPosition($token->offset);
			$tokenFactory->setType($token->type);

			$objects[] = $tokenFactory;
		}

		return $objects;
	}

}
