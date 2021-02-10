<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Exceptions;


use InvalidArgumentException;
use Mathematicator\Tokenizer\Token\IToken;

class TokenizerException extends InvalidArgumentException
{
	/**
	 * @throws TokenizerException
	 */
	public static function tokenMustBeIToken(mixed $token): void
	{
		throw new self(
			'Token must be instance of "' . IToken::class . '", but type "'
			. (is_object($token) ? get_class($token) : json_encode($token, JSON_THROW_ON_ERROR)) . '" given.',
		);
	}
}
