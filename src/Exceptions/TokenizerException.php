<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer\Exceptions;


use Exception;
use Mathematicator\Tokenizer\Token\IToken;

class TokenizerException extends Exception
{

	/**
	 * @param mixed $token
	 * @throws TokenizerException
	 */
	public static function tokenMustBeIToken($token): void
	{
		throw new self(
			'Token must be instance of "' . IToken::class . '", but type "'
			. (is_object($token) ? get_class($token) : json_encode($token)) . '" given.'
		);
	}
}
