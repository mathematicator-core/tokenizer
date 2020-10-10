<?php

declare(strict_types=1);

namespace Mathematicator\Tokenizer;


use Mathematicator\Tokenizer\Exceptions\TokenizerException;
use Mathematicator\Tokenizer\Token\IToken;

class TokenIterator
{

	/** @var IToken[] */
	private $tokens;

	/** @var int */
	private $iterator = 0;


	/**
	 * @param IToken[] $tokens
	 * @throws TokenizerException
	 */
	public function __construct(array $tokens)
	{
		foreach ($tokens as $token) {
			if (!$token instanceof IToken) {
				TokenizerException::tokenMustBeIToken($token);
			}
		}

		$this->tokens = $tokens;
	}


	public function getCount(): int
	{
		return \count($this->tokens);
	}


	public function getToken(): ?IToken
	{
		return $this->tokens[$this->iterator] ?? null;
	}


	public function setToken(IToken $token): void
	{
		$this->tokens[$this->iterator] = $token;
	}


	public function getIterator(): int
	{
		return $this->iterator;
	}


	public function next(int $times = 1): void
	{
		$this->iterator += $times;
	}


	public function getNextToken(int $step = 1): ?IToken
	{
		return $this->tokens[$this->iterator + $step] ?? null;
	}


	public function last(): void
	{
		$this->iterator--;
	}


	public function getLastToken(int $step = 1): ?IToken
	{
		return $this->tokens[$this->iterator - $step] ?? null;
	}


	public function isFinal(): bool
	{
		return !isset($this->tokens[$this->iterator]);
	}


	/**
	 * @return IToken[]
	 */
	public function getTokens(): array
	{
		return $this->tokens;
	}
}
