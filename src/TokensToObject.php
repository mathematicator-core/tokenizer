<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


use Mathematicator\Numbers\Converter\RomanToInt;
use Mathematicator\Numbers\Exception\NumberException;
use Mathematicator\Numbers\SmartNumber;
use Mathematicator\Tokenizer\Token\ComparatorToken;
use Mathematicator\Tokenizer\Token\EquationToken;
use Mathematicator\Tokenizer\Token\FactorialToken;
use Mathematicator\Tokenizer\Token\FunctionToken;
use Mathematicator\Tokenizer\Token\InfinityToken;
use Mathematicator\Tokenizer\Token\IToken;
use Mathematicator\Tokenizer\Token\NumberToken;
use Mathematicator\Tokenizer\Token\OperatorToken;
use Mathematicator\Tokenizer\Token\OtherToken;
use Mathematicator\Tokenizer\Token\PiToken;
use Mathematicator\Tokenizer\Token\RomanNumberToken;
use Mathematicator\Tokenizer\Token\SubToken;
use Mathematicator\Tokenizer\Token\VariableToken;
use Nette\Tokenizer\Token;

final class TokensToObject
{
	/**
	 * @param Token[] $tokens
	 * @return IToken[]
	 * @throws NumberException
	 */
	public function toObject(array $tokens): array
	{
		$objects = [];
		for ($iterator = 0; isset($tokens[$iterator]); $iterator++) {
			$token = $tokens[$iterator];
			switch ($token->type) {
				case Tokens::M_NUMBER:
					$tokenFactory = new NumberToken(SmartNumber::of($token->value));
					break;

				case Tokens::M_ROMAN_NUMBER:
					$tokenFactory = new RomanNumberToken(
						SmartNumber::of(RomanToInt::convert($token->value)),
					);
					break;

				case Tokens::M_VARIABLE:
					$tokenFactory = new VariableToken(
						$token->value,
						SmartNumber::of(1),
					);
					break;

				case Tokens::M_FACTORIAL:
					$tokenFactory = new FactorialToken(
						SmartNumber::of(str_replace('!', '', $token->value)),
					);
					break;

				case Tokens::M_INFINITY:
					$tokenFactory = new InfinityToken;
					break;

				case Tokens::M_OPERATOR:
					$tokenFactory = new OperatorToken;
					$tokenFactory->setPriority($token->value);
					break;

				case Tokens::M_EQUATION:
					$tokenFactory = new EquationToken;
					break;

				case Tokens::M_COMPARATOR:
					$tokenFactory = new ComparatorToken;
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

				case Tokens::M_PI:
					$tokenFactory = new PiToken(SmartNumber::of(M_PI));
					break;

				default:
					$tokenFactory = new OtherToken;
			}

			$objects[] = $tokenFactory->setToken($token->value)
				->setPosition($token->offset)
				->setType((string) $token->type);
		}

		return $objects;
	}
}
